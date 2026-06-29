//
import Sortable from 'sortablejs';

window.initAdminSortable = function (el, wire, method) {
    if (! el || el.dataset.sortableInit) {
        return;
    }

    el.dataset.sortableInit = '1';

    Sortable.create(el, {
        handle: '.drag-handle',
        draggable: '[data-sort-id]',
        animation: 150,
        ghostClass: 'opacity-40',
        onEnd: () => {
            const ids = [...el.querySelectorAll('[data-sort-id]')]
                .map((row) => row.dataset.sortId);

            if (ids.length) {
                wire.call(method, ids);
            }
        },
    });
};

document.addEventListener('livewire:navigated', () => {
    document.querySelectorAll('[data-sortable-list]').forEach((el) => {
        delete el.dataset.sortableInit;
    });
});
