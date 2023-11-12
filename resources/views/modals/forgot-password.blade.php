<div class="modal forgot-password-modal" id="forgot-password-modal">
    <div class="modal-inner">
        {{-- Modal Dialog start --}}
        <div class="modal-dialog">

            {{-- Modal Header start --}}
            <div class="modal-dialog__header">
                <div class="modal-dialog__header-inner">
                    <h2 class="modal-dialog__title">Танзими гузарвожа</h2>
                    <button class="modal-dissmiss" data-action="hide-modal" data-target-id="forgot-password-modal">X</button>
                </div>
            </div>  {{-- Modal Header end --}}

            {{-- Modal Body start --}}
            <div class="modal-dialog__body">
                <div class="modal-dialog__body-inner">
                    <form class="form modal-form" action="/forgot-password" method="POST" id="forgot-password-form">
                        <div class="form-group">
                            <label>Гузарвожаи худро фаромӯш кардаед? Фақат нишонии имейли худро ба мо бидиҳед ва мо пайванди бознишонии гузарвожаро барои шумо ирсол мекунем, ки ба шумо имкон медиҳад, то гузарвожаи наве танзим кунед.</label>

                            <input class="input input--light" type="email" placeholder="Почтаи электронӣ" name="email" required>
                        </div>

                        <ul class="modal-form__errors"></ul>

                        <button class="button button--main modal-submit">
                            <span class="material-icons modal-submit-icon">lock_reset</span> Дархости пайванд
                        </button>
                    </form>
                </div>  {{-- Modal Body Inner end --}}
            </div>  {{-- Modal Body end --}}

        </div>  {{-- Modal Dialog end --}}
    </div>
</div>
