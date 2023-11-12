@extends('layouts.app')
@section('main')

<div class="main__content profile-page-content">
    <section class="theme-styled-block profile-section">
        <div class="profile-section__inner">

            <form class="form main-form profile-update-form" action="{{ route('users.update') }}" method="POST" id="profile-update-form" enctype="multipart/form-data">
                @csrf

                {{-- Profile Settings --}}
                <div class="main-form__block">
                    <h1 class="main-title main-title--indented">Танзимоти намоя</h1>

                    <div class="form-group">
                        @error('name')
                            <span class="form-error-message">{{ $message }}</span>
                        @enderror

                        <div class="block_with_edit_button @error('name') block_with_edit_button--error @enderror">
                            <input class="input" name="name" type="text" value="{{ old('name') != '' ? old('name') : $user->name }}" placeholder="Ном" readonly required>
                            <button class="button" type="button" data-action="enable-readonly-input" data-target-input-name="name"><span class="material-icons">edit</span> Вироиш</button>
                        </div>
                    </div>

                    <div class="form-group">
                        @error('email')
                            <span class="form-error-message">{{ $message }}</span>
                        @enderror

                        <div class="block_with_edit_button @error('email') block_with_edit_button--error @enderror">
                            <input class="input" name="email" type="email" value="{{ old('email') != '' ? old('email') : $user->email }}" placeholder="Почтаи электронӣ" readonly
                                required>
                            <button class="button" type="button" data-action="enable-readonly-input" data-target-input-name="email"><span class="material-icons">edit</span> Вироиш</button>
                        </div>
                    </div>

                    <div class="form-group selectize-container">
                        <select class="selectize-singular" name="gender" id="gender-select" required>
                            <option value="male" @selected($user->gender == 'male')>Мард</option>
                            <option value="female" @selected($user->gender == 'female')>Зан</option>
                        </select>
                    </div>

                    <div class="profile-form__image-group">
                        <img class="profile-form__image-group-image" src="{{ asset('img/users/' . $user->image) }}" alt="{{ $user->name }}" id="profile-form-image">

                        {{-- Hidden input --}}
                        <input class="profile-form__image-group-input" name="image" type="file" id="profile-form-image-input">

                        <div class="profile-form__image-group-actions">
                            <label class="profile-form__image-group-label" for="profile-form-image-input"><span class="material-icons">edit</span> Вироиш</label>

                            <button class="profile-form__image-group-remove-btn" type="button" id="profile-form-image-remove-btn">Ҳазф кардан</button>
                            {{-- True when user clicks remove image button --}}
                            <input type="hidden" value="0" name="remove_image" id="profile-form-image-remove-input">
                        </div>
                    </div>
                </div> {{-- /end Profile Settings --}}

                {{-- Password --}}
                <div class="main-form__block">
                    <h1 class="main-title">Ивази калидвожа</h1>

                    <div class="form-group">
                        @error('old_password')
                            <span class="form-error-message">{{ $message }}</span>
                        @enderror

                        <div class="block_with_edit_button @error('old_password') block_with_edit_button--error @enderror">
                            <input class="input" name="old_password" type="password" placeholder="Калидвожаи пешина" minlength="5" readonly>
                            <button class="button" type="button" data-action="enable-readonly-input" data-target-input-name="old_password"><span class="material-icons">edit</span> Вироиш</button>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="block_with_edit_button">
                            <input class="input" name="new_password" type="password" placeholder="Калидвожаи нав" minlength="5" autocomplete="new-password" readonly>
                        </div>
                    </div>
                </div> {{-- /end Password --}}

                {{-- About --}}
                <div class="main-form__block">
                    <h1 class="main-title">Мухтасар дар бораи ман майлу хоҳишҳоям</h1>

                    <div class="form-group">
                        <div class="block_with_edit_button block_with_edit_button--columned">
                            <textarea class="textarea" name="biography" placeholder="Мухтасар дар бораи ман майлу хоҳишҳоям" rows="7"
                                readonly>{{ $user->biography }}</textarea>
                            <button class="button" type="button" data-action="enable-readonly-input" data-target-input-name="biography"><span class="material-icons">edit</span> Вироиш</button>
                        </div>
                    </div>
                </div> {{-- /end About --}}

                <button class="button button--main main-form__submit">Захираи тағйирот</button>

                <x-terms-of-use class="accept-terms_with_dark_checkbox" id="profile-update-terms" />
            </form>

        </div>
    </section>
</div>

@endsection
