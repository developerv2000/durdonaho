// Initialize global vars
let spinner = document.querySelector('#spinner');

// Register Service worker for PWA
if ('serviceWorker' in navigator) {
    // Register a service worker hosted at the root of the
    // site using the default scope.
    navigator.serviceWorker.register(document.location.origin + '/service-worker.js').then(function (registration) {
        console.log('Service worker registration succeeded:', registration);
    }, /*catch*/ function (error) {
        console.log('Service worker registration failed:', error);
    });
} else {
    console.log('Service workers are not supported.');
}

// Document onclick listeners
document.body.addEventListener('click', function (evt) {
    if (evt.target.dataset.action == 'like') {
        like(evt.target);
    } else if (evt.target.dataset.action == 'favorite') {
        favorite(evt.target);
    }
});


// initialize components
$(document).ready(function () {
    $('.selectize-singular').selectize({
        //options
    });

    $('.selectize-singular-taggable').selectize({
        persist: false,
        create: function (input) {
            return {
                value: input,
                text: input,
            };
        },
        render: {
            option_create: function (data, escape) {
                return '<div class="create">Изофа кардан <strong>' + escape(data.input) + '</strong>&hellip;</div>';
            }
        }
    });

    $('.selectize-singular-linked').selectize({
        onChange(value) {
            window.location = value;
        }
    });

    $('.selectize-multiple').selectize({
        // options
    });

    $('.selectize-multiple-taggable').selectize({
        delimiter: ",",
        persist: false,
        create: function (input) {
            return {
                value: input,
                text: input,
            };
        },
        render: {
            option_create: function (data, escape) {
                return '<div class="create">Изофа кардан <strong>' + escape(data.input) + '</strong>&hellip;</div>';
            }
        }
    });
});


// Ajax CSRF-Token initialization
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});


// debounce function
function debounce(callback, timeoutDelay = 500) {
    let timeoutId;

    return (...rest) => {
        clearTimeout(timeoutId);
        timeoutId = setTimeout(() => callback.apply(this, rest), timeoutDelay);
    };
}


// Toggle mobile menu
document.querySelector('[data-action="show-mobile-menu"]').addEventListener('click', (evt) => {
    document.querySelector('.mobile-menu').classList.add('mobile-menu--visible');
    document.body.style.overflowY = 'hidden';
});

document.querySelector('[data-action="hide-mobile-menu"]').addEventListener('click', (evt) => {
    document.querySelector('.mobile-menu').classList.remove('mobile-menu--visible');
    document.body.style.overflowY = 'auto';
});


// card carousels
$('.card-carousel').owlCarousel({
    loop: true,
    margin: 0,
    nav: true,
    navText: ['<span class="material-icons-outlined">arrow_back_ios</span>', '<span class="material-icons-outlined">arrow_forward_ios</span>'],
    items: 1,
    dots: false,
    singleItem: true,
    autoHeight: true,
    transitionStyle: "fade"
});


// dropdown
document.querySelectorAll('.dropdown__button').forEach(item => {
    item.addEventListener('click', (evt) => {
        item.closest('.dropdown').classList.add('dropdown--opened');
    })
})

document.querySelectorAll('.dropdown__background').forEach(item => {
    item.addEventListener('click', (evt) => {
        item.closest('.dropdown').classList.remove('dropdown--opened');
    })
})


// modals
document.querySelectorAll('[data-action="show-modal"]').forEach(item => {
    item.addEventListener('click', (evt) => {
        document.getElementById(item.dataset.targetId).classList.add('modal--visible');
        document.body.style.overflowY = "hidden";
    });
});

// hide modals
document.querySelectorAll('[data-action="hide-modal"]').forEach(item => {
    item.addEventListener('click', (evt) => {
        document.body.style.overflowY = "auto";
        document.getElementById(item.dataset.targetId).classList.remove('modal--visible');
    });
});

// Login Modals Register button
document.querySelector('#login-modal-register-button').addEventListener('click', (evt) => {
    document.querySelector('#register-modal').classList.add('modal--visible');
    document.querySelector('#login-modal').classList.remove('modal--visible');
});

// Login Modals Forgot Password button
document.querySelector('#login-modal-forgot-password').addEventListener('click', (evt) => {
    document.querySelector('#forgot-password-modal').classList.add('modal--visible');
    document.querySelector('#login-modal').classList.remove('modal--visible');
});


// Aside read more button on mobile devices
let asideTextMore = document.querySelector('.aside-text__more');
if (asideTextMore) {
    asideTextMore.addEventListener('click', (evt) => {
        document.querySelector('.aside-text__body').classList.add('aside-text__body--full');
        asideTextMore.style.display = 'none';
    });
}


// Initialize Report Modals
// Function is also called after ajax quotes/authors list update
function initializeReportModals() {
    document.querySelectorAll('[data-action="show-report-bug-modal"]').forEach((item) => {
        item.addEventListener('click', (evt) => {
            document.body.style.overflowY = "hidden";
            let modal = document.querySelector('#report-bug-modal');

            // validate input values
            let quoteId = modal.querySelector('[name="quote_id"]');
            quoteId.value = item.dataset.quoteId ? item.dataset.quoteId : null;

            let authorId = modal.querySelector('[name="author_id"]');
            authorId.value = item.dataset.authorId ? item.dataset.authorId : null;

            //show modal
            modal.classList.add('modal--visible');
        });
    });
}

initializeReportModals();

// Ajax Register
let registerForm = document.querySelector('#register-form');
if (registerForm) {
    registerForm.addEventListener('submit', (evt) => {
        evt.preventDefault();
        spinner.classList.add('spinner--show');

        $.ajax({
            type: 'POST',
            enctype: 'multipart/form-data',
            url: '/register',
            data: new FormData(registerForm),
            processData: false,
            contentType: false,
            success: function (response) {
                // reload page on success
                if (response.validation == 'success') {
                    window.location.reload();
                } else if (response.validation == 'failed') {
                    // else display error messages
                    let errorsList = registerForm.querySelector('.modal-form__errors');
                    errorsList.innerHTML = '';
                    for (let message of response.errorMessages) {
                        errorsList.innerHTML += `<li>${message}</li>`
                    };

                    // unhighlight all previous failed inputs
                    let failedInputs = registerForm.querySelectorAll('.input--error');
                    for (let failedInput of failedInputs) {
                        failedInput.classList.remove('input--error');
                    }

                    // highlight failed inputs
                    for (let failedInput of response.failedInputs) {
                        registerForm.querySelector(`[name="${failedInput}"]`).classList.add('input--error');
                    }
                }

                spinner.classList.remove('spinner--show');
            },
            error: function () {
                spinner.classList.remove('spinner--show');
                console.log('Ajax register failed !');
            }
        });
    });
};


// Ajax Login
let loginForm = document.querySelector('#login-form');
if (loginForm) {
    loginForm.addEventListener('submit', (evt) => {
        evt.preventDefault();
        spinner.classList.add('spinner--show');

        $.ajax({
            type: 'POST',
            enctype: 'multipart/form-data',
            url: '/login',
            data: new FormData(loginForm),
            processData: false,
            contentType: false,
            success: function (response) {
                // reload page on success
                if (response == 'success') {
                    window.location.reload();
                } else if (response == 'failed') {
                    // else display error messages
                    let errorsList = loginForm.querySelector('.modal-form__errors');
                    errorsList.innerHTML = '<li>Номи корбарӣ ё рамз иштибоҳ аст</li>';
                }

                spinner.classList.remove('spinner--show');
            },
            error: function () {
                spinner.classList.remove('spinner--show');
                console.log('Ajax login failed !');
            }
        });

    });
};


// Ajax Verification Resend Email
let verificationResendForm = document.querySelector('#verification-resend-email-form');
if (verificationResendForm) {
    verificationResendForm.addEventListener('submit', (evt) => {
        evt.preventDefault();
        spinner.classList.add('spinner--show');

        $.ajax({
            type: 'POST',
            enctype: 'multipart/form-data',
            url: '/resend-email',
            data: new FormData(verificationResendForm),
            processData: false,
            contentType: false,
            success: function () {
                window.location.reload();
                spinner.classList.remove('spinner--show');
            },
            error: function () {
                spinner.classList.remove('spinner--show');
                console.log('Ajax Verification Resend Email failed !');
            }
        });

    });
};


// Ajax Forgot Password
let forgotPasswordForm = document.querySelector('#forgot-password-form');
if (forgotPasswordForm) {
    forgotPasswordForm.addEventListener('submit', (evt) => {
        evt.preventDefault();
        spinner.classList.add('spinner--show');

        $.ajax({
            type: 'POST',
            enctype: 'multipart/form-data',
            url: '/forgot-password',
            data: new FormData(forgotPasswordForm),
            processData: false,
            contentType: false,
            success: function (response) {
                // reload page on success
                if (response == 'success') {
                    window.location.reload();
                } else if (response == 'failed') {
                    // else display error messages
                    let errorsList = forgotPasswordForm.querySelector('.modal-form__errors');
                    errorsList.innerHTML = '<li>Корбаре бо чунин имейл вуҷуд надорад!</li>';
                }

                spinner.classList.remove('spinner--show');
            },
            error: function () {
                spinner.classList.remove('spinner--show');
                console.log('Ajax forgot password failed !');
            }
        });

    });
};


// Aside categories search
let asideSearchInput = document.querySelector('#aside-search-input');
if (asideSearchInput) {
    let categoryEls = document.querySelectorAll('.aside-categories__item');

    asideSearchInput.addEventListener('input', debounce((evt) => {
        categoryEls.forEach(item => {
            item.children[0].textContent.toLowerCase().includes(evt.target.value.toLowerCase()) ? item.style.display = '' : item.style.display = 'none';
        });
    }));
}


//--------------- Categories Filter & search start---------------
let filterForm = document.querySelector('#categories-filter-form');
if (filterForm) {
    filterForm.addEventListener('submit', (evt) => {
        evt.preventDefault();
    })
}

// on all categories buttons click
let filterCatAllBtn = document.querySelector('#categories-filter-all-btn');
if (filterCatAllBtn) {
    filterCatAllBtn.addEventListener('click', (evt) => {
        // uncheck all checked categories
        let checkboxes = filterForm.querySelectorAll('input[name=category_id]:checked');

        for (chb of checkboxes) {
            chb.checked = false;
        }

        // add active class
        filterCatAllBtn.classList.add('categories-filter__button--active');

        // update list
        ajaxUpdateList();
    });
}

// on more categories buttons click
let filterCatMoreBtn = document.querySelector('#categories-filter-more-btn');
if (filterCatMoreBtn) {
    filterCatMoreBtn.addEventListener('click', (evt) => {
        // display all hidden categories
        let hiddenChbs = document.querySelectorAll('.categories-filter__checkbox--hidden');

        for (let i = 0; i < hiddenChbs.length; i++) {
            hiddenChbs[i].classList.remove('categories-filter__checkbox--hidden');
        }
        // hide button
        filterCatMoreBtn.style.display = 'none';
    });
}

// run AJAX function to update quotes or authors list on any filters checkbox change
document.querySelectorAll('.categories-filter__checkbox').forEach(item => {
    item.addEventListener('change', (evt) => {
        let checkedChbs = filterForm.querySelectorAll('input[name=category_id]:checked');

        // remove active class from all categories button case any categories selected
        if (checkedChbs.length) {
            filterCatAllBtn.classList.remove('categories-filter__button--active');
        } else {
            filterCatAllBtn.classList.add('categories-filter__button--active');
        }

        // update list
        ajaxUpdateList();
    });
});

// run AJAX function to update quotes/authors list on search input value change
let catSearchInput = document.querySelector('#categories-filter-search-input');
if (catSearchInput) {
    catSearchInput.addEventListener('input', debounce((evt) => {
        ajaxUpdateList();
    }));
}

// AJAX update quotes or authors list (on categories filter or search values change)
function ajaxUpdateList() {
    let formData = new FormData(filterForm);
    // append joined arrays into FormData because FormData doesnt support arrays
    let categoryIds = formData.getAll('category_id');
    let joinedIds = categoryIds.join('-');
    formData.append('category_id', joinedIds);

    $.ajax({
        type: "POST",
        enctype: "multipart/form-data",
        url: filterForm.action,
        data: formData,
        processData: false,
        contentType: false,

        success: function (response) {
            let list = document.getElementById('main-list');
            list.innerHTML = response;

            // reinitialize yandex share buttons
            list.querySelectorAll('.ya-share2').forEach(item => {
                Ya.share2(item, {});
            });

            // reinitialize report modals
            initializeReportModals();
        },

        error: function () {
            console.log("Ajax update list error!");
        }
    });
}
//--------------- Categories Filter & search start---------------


//------------- Like and Favorite actions-------------
function like(target) {
    $.ajax({
        type: 'POST',
        url: '/like',
        data: { quote_id: target.dataset.quoteId, author_id: target.dataset.authorId },

        success: function (response) {
            // change like button for all of the identic cards
            let parentCard = target.closest('.card');

            document.querySelectorAll('[data-card-id="' + parentCard.dataset.cardId + '"]').forEach(item => {
                let likeIcon = item.getElementsByClassName('card__actions-like-icon')[0];
                let likesCount = item.getElementsByClassName('card__actions-like-counter')[0];

                likesCount.innerHTML = response.likesCount;
                if (response.status == 'liked') {
                    likeIcon.innerHTML = 'favorite';
                } else if (response.status == 'unliked') {
                    likeIcon.innerHTML = 'favorite_border';
                }
            });
        },

        error: function () {
            console.log("Ajax like error!");
        }

    });
}

function favorite(target) {
    $.ajax({
        type: 'POST',
        url: '/favorite',
        data: { quote_id: target.dataset.quoteId, author_id: target.dataset.authorId },

        success: function (response) {
            // change favorite button for all of the identic cards
            let parentCard = target.closest('.card');

            document.querySelectorAll('[data-card-id="' + parentCard.dataset.cardId + '"]').forEach(item => {
                let favoriteIcon = item.getElementsByClassName('card__actions-favorite-icon')[0];

                if (response.status == 'added-into-favorites') {
                    favoriteIcon.classList = 'material-icons card__actions-favorite-icon';
                } else if (response.status == 'removed-from-favorites') {
                    favoriteIcon.classList = 'material-icons-outlined card__actions-favorite-icon';
                }
            });
        },

        error: function () {
            console.log("Ajax favorite error!");
        }

    });
}
//------------- Like and Favorite actions-------------


//------------- Profile update -------------
let profileUpdateForm = document.querySelector('#profile-update-form');
if (profileUpdateForm) {
    profileUpdateForm.addEventListener('submit', (evt) => {
        spinner.classList.add('spinner--show');
    });

    //remove readonly attr from input or textarea and set focus to needed input
    document.querySelectorAll('[data-action="enable-readonly-input"]').forEach((item) => {
        item.addEventListener('click', (evt) => {
            // remove readonly attr from input or textarea
            let parent = item.closest('.form-group');

            parent.querySelectorAll('input').forEach((item) => {
                item.readOnly = false;
            });

            parent.querySelectorAll('textarea').forEach((item) => {
                item.readOnly = false;
            });

            // also remove readonly from new_password input on edit password button click
            if (item.dataset.targetInputName == 'old_password') {
                profileUpdateForm.querySelector('[name="new_password"]').readOnly = false;

                // and add required for password fields
                profileUpdateForm.querySelector('[name="new_password"]').required = true;
                profileUpdateForm.querySelector('[name="old_password"]').required = true;
            }

            // set caret to the end of the input and focus it
            let input = profileUpdateForm.querySelector('[name="' + item.dataset.targetInputName + '"]');
            // input element's type ('email') does not support selection
            if (input.name != 'email') {
                input.setSelectionRange(input.value.length, input.value.length);
            }
            input.focus();

            // hide button
            item.style.display = 'none';
        });
    });


    let img = document.querySelector('#profile-form-image');
    let imgInput = document.querySelector('#profile-form-image-input');
    let imgRemoveBtn = document.querySelector('#profile-form-image-remove-btn');
    let imgRemoveInput = document.querySelector('#profile-form-image-remove-input');

    // set default image on remove image button click
    imgRemoveBtn.addEventListener('click', (evt) => {
        imgInput.value = null;
        img.src = '/img/users/__default.jpg';
        imgRemoveInput.value = 1;
    });

    // Show image from local on image input change
    imgInput.addEventListener('change', (evt) => {
        let file = evt.target.files[0];
        let imageType = /image.*/;

        if (file) {
            if (file.type.match(imageType)) {
                img.src = URL.createObjectURL(file);
                imgRemoveInput.value = 0;
                // set default image if format of file not supported
            } else {
                imgInput.value = '';
                img.src = '/img/users/__default.jpg';
                imgRemoveInput.value = 1;

                alert('Формат файла не поддерживается!');
            }
            // set default image on cancel click
        } else {
            img.src = '/img/users/__default.jpg';
            imgRemoveInput.value = 1;
        }
    });
}
//------------- Profile edit -------------


//------------- Quotes create & update forms -------------
let storeQuotesForm = document.querySelector('#store-quotes-form');
if (storeQuotesForm) {
    storeQuotesForm.addEventListener('submit', () => {
        spinner.classList.add('spinner--show');
    });
}

let updateQuotesForm = document.querySelector('#update-quotes-form');
if (updateQuotesForm) {
    updateQuotesForm.addEventListener('submit', () => {
        spinner.classList.add('spinner--show');
    });
}
//------------- Quotes create & update forms -------------


//------------- Validating Source Inputs while Creating / Updating quote (visibility and required statements) -------------
function validateSourceInputs(key) {
    document.querySelectorAll(['[data-source-key]']).forEach((item) => {
        if (item.dataset.sourceKey == key) {
            item.classList.remove('form-group--hidden');

            item.querySelectorAll('input').forEach((item) => {
                item.required = true;
            });

            item.querySelectorAll('select').forEach((item) => {
                item.required = true;
            });
        }

        else {
            item.classList.add('form-group--hidden');

            item.querySelectorAll('input').forEach((item) => {
                item.required = false;
            });

            item.querySelectorAll('select').forEach((item) => {
                item.required = false;
            });
        }
    });
}

let sourceSelect = document.querySelector('select[name="source_key"]');
if (sourceSelect) {
    $('.source-selectize').selectize({
        onChange(value) {
            validateSourceInputs(value);
        }
    });

    // activeSource is initialized via PHP on blade view
    validateSourceInputs(activeSource);
}
//------------- Validating Source Inputs while Creating / Updating quote -------------
