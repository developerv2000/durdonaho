@if($errors->any())
    <div class="alert alert-errors">
        <h3 class="alert-title"><span class="material-icons alert-icon">warning</span>
            Ошибка! Пожалуйста исправьте ошибки и попробуйте заново.
        </h3>

        <ul class="alert-list">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif