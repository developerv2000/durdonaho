<div class="modal fade" id="destroy-single-item-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Удалить</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form action="{{ route($modelShortcut . '.destroy') }}" method="POST">
                {{ csrf_field() }}
                {{-- itemId = 0 used while initializing index pages --}}
                <input type="hidden" value="{{ isset($destroyItemId) ? $destroyItemId : 0 }} " name="id" id="destroy-single-item-modal-input" />

                <div class="modal-body">
                    Вы уверены что хотите удалить ?
                </div>

                <div class="modal-footer">
                    <button type="button" class="button button--secondary" data-bs-dismiss="modal">Отмена</button>
                    <button type="submit" class="button button--danger">Удалить</button>
                </div>
            </form>
        </div>
    </div>
</div>