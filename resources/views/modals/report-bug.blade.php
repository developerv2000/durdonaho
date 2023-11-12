<div class="modal report-bug-modal" id="report-bug-modal">
    <div class="modal-inner">
        {{-- Modal Dialog start --}}
        <div class="modal-dialog">

            {{-- Modal Header start --}}
            <div class="modal-dialog__header">
                <div class="modal-dialog__header-inner">
                    <h2 class="modal-dialog__title">Гузориши саҳв</h2>
                    <button class="modal-dissmiss" data-action="hide-modal" data-target-id="report-bug-modal">X</button>
                </div>
            </div>  {{-- Modal Header end --}}

            {{-- Modal Body start --}}
            <div class="modal-dialog__body">
                <div class="modal-dialog__body-inner">
                    <form class="form modal-form" action="{{ route('reports.store') }}" method="POST" id="report-bug-form">
                        <p class="report-bug-modal__text">Шумо метавонед шикоятҳоро танҳо пас аз номнависӣ ирсол кунед</p>

                        @csrf
                        <input type="hidden" name="quote_id">
                        <input type="hidden" name="author_id">

                        <div class="form-group">
                            <label class="label" for="report-message">Матни хато ё шикоятро ворид созед</label>
                            <textarea class="textarea textarea--light" name="message" rows="6" id="report-message" required></textarea>
                        </div>

                        <button class="button button--main modal-submit">Ирсол</button>

                        <x-terms-of-use id="report-bug-terms" />
                    </form>
                </div>  {{-- Modal Body Inner end --}}
            </div>  {{-- Modal Body end --}}

        </div>  {{-- Modal Dialog end --}}
    </div>
</div>
