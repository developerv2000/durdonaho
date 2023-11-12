<div class="modal login-modal" id="login-modal">
    <div class="modal-inner">
        {{-- Modal Dialog start --}}
        <div class="modal-dialog">

            {{-- Modal Header start --}}
            <div class="modal-dialog__header">
                <div class="modal-dialog__header-inner">
                    <h2 class="modal-dialog__title">Вуруд</h2>
                    <button class="modal-dissmiss" data-action="hide-modal" data-target-id="login-modal">X</button>
                </div>
            </div>  {{-- Modal Header end --}}

            {{-- Modal Body start --}}
            <div class="modal-dialog__body">
                <div class="modal-dialog__body-inner">
                    <form class="form modal-form" action="/login" method="POST" id="login-form">
                        <input type="hidden" name="ajax" value="1">

                        <div class="form-group">
                            <input class="input input--light" type="email" placeholder="Почтаи электронӣ" name="email" required>
                        </div>

                        <div class="form-group">
                            <input class="input input--light" type="password" placeholder="Рамз" name="password" autocomplete="current-password" required>
                        </div>

                        <ul class="modal-form__errors"></ul>

                        <button class="button button--main modal-submit">
                            <span class="material-icons">sensor_door</span> Вуруд
                        </button>
                    </form>

                    <div class="login-modal__additional-actions">
                        <button class="login-modal__forgot-password" id="login-modal-forgot-password">Рамзатонро фаромӯш кардед? </button>
                        <button class="button--transparent login-modal__register" id="login-modal-register-button">Номнависӣ</button>
                    </div>
                </div>  {{-- Modal Body Inner end --}}
            </div>  {{-- Modal Body end --}}

        </div>  {{-- Modal Dialog end --}}
    </div>
</div>
