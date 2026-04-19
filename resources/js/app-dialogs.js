/**
 * Custom modal confirm / alert (replaces window.confirm / window.alert).
 */

let dialogRoot = null;
let pendingResolve = null;
let currentMode = 'confirm';

function onKeydown(ev) {
    if (ev.key === 'Escape' && pendingResolve) {
        ev.preventDefault();
        closeDialog(currentMode === 'alert' ? true : false);
    }
}

function ensureDialog() {
    if (dialogRoot) {
        return dialogRoot;
    }

    const root = document.createElement('div');
    root.id = 'app-dialog-root';
    root.setAttribute('role', 'dialog');
    root.setAttribute('aria-modal', 'true');
    root.className =
        'fixed inset-0 z-[200] flex items-end justify-center p-4 sm:items-center pointer-events-none opacity-0 transition-opacity duration-200 [&.is-open]:pointer-events-auto [&.is-open]:opacity-100';
    root.innerHTML = `
        <div class="app-dialog-backdrop absolute inset-0 bg-zinc-950/60 backdrop-blur-sm" aria-hidden="true"></div>
        <div class="relative z-10 w-full max-w-md rounded-2xl border border-zinc-200/90 bg-white p-6 shadow-2xl ring-1 ring-zinc-200/50 dark:border-zinc-700 dark:bg-zinc-900 dark:ring-zinc-800">
            <h3 id="app-dialog-title" class="text-lg font-semibold text-zinc-900 dark:text-zinc-50"></h3>
            <p id="app-dialog-message" class="mt-3 whitespace-pre-wrap text-sm leading-relaxed text-zinc-600 dark:text-zinc-300"></p>
            <div id="app-dialog-actions" class="mt-6 flex flex-wrap justify-end gap-2"></div>
        </div>
    `;
    document.body.appendChild(root);

    root.querySelector('.app-dialog-backdrop').addEventListener('click', () => {
        closeDialog(currentMode === 'alert' ? true : false);
    });

    dialogRoot = root;
    return root;
}

function setOpen(open) {
    const root = ensureDialog();
    root.classList.toggle('is-open', open);
    if (open) {
        document.documentElement.classList.add('overflow-hidden');
        window.addEventListener('keydown', onKeydown);
    } else {
        document.documentElement.classList.remove('overflow-hidden');
        window.removeEventListener('keydown', onKeydown);
    }
}

function closeDialog(result) {
    const fn = pendingResolve;
    pendingResolve = null;
    setOpen(false);
    if (typeof fn === 'function') {
        fn(result);
    }
}

function buildActions(mode, { confirmLabel, cancelLabel, okLabel }) {
    const actions = ensureDialog().querySelector('#app-dialog-actions');
    actions.innerHTML = '';

    if (mode === 'alert') {
        const ok = document.createElement('button');
        ok.type = 'button';
        ok.className =
            'rounded-xl bg-brand-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2 dark:focus:ring-offset-zinc-900';
        ok.textContent = okLabel || 'OK';
        ok.addEventListener('click', () => closeDialog(true));
        actions.appendChild(ok);
        return;
    }

    const cancel = document.createElement('button');
    cancel.type = 'button';
    cancel.className =
        'rounded-xl border border-zinc-200 px-4 py-2.5 text-sm font-medium text-zinc-700 hover:bg-zinc-50 dark:border-zinc-600 dark:text-zinc-200 dark:hover:bg-zinc-800';
    cancel.textContent = cancelLabel || 'Cancel';
    cancel.addEventListener('click', () => closeDialog(false));

    const confirm = document.createElement('button');
    confirm.type = 'button';
    confirm.className =
        'rounded-xl bg-red-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-red-500 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-zinc-900';
    confirm.textContent = confirmLabel || 'OK';
    confirm.addEventListener('click', () => closeDialog(true));

    actions.appendChild(cancel);
    actions.appendChild(confirm);
}

/**
 * @param {{ message: string, title?: string, confirmLabel?: string, cancelLabel?: string }} opts
 * @returns {Promise<boolean>}
 */
export function showAppConfirm(opts = {}) {
    const { message, title = '', confirmLabel, cancelLabel } = opts;
    return new Promise((resolve) => {
        ensureDialog();
        currentMode = 'confirm';
        pendingResolve = resolve;
        const titleEl = ensureDialog().querySelector('#app-dialog-title');
        titleEl.textContent = title || '';
        titleEl.classList.toggle('hidden', !(title && String(title).trim()));
        ensureDialog().querySelector('#app-dialog-message').textContent = message || '';
        buildActions('confirm', { confirmLabel, cancelLabel });
        setOpen(true);
    });
}

/**
 * @param {{ message: string, title?: string, okLabel?: string }} opts
 * @returns {Promise<void>}
 */
export function showAppAlert(opts = {}) {
    const { message, title = '', okLabel } = opts;
    return new Promise((resolve) => {
        ensureDialog();
        currentMode = 'alert';
        pendingResolve = () => {
            resolve();
        };
        const titleEl = ensureDialog().querySelector('#app-dialog-title');
        titleEl.textContent = title || '';
        titleEl.classList.toggle('hidden', !(title && String(title).trim()));
        ensureDialog().querySelector('#app-dialog-message').textContent = message || '';
        buildActions('alert', { okLabel });
        setOpen(true);
    });
}

function setupFormConfirmDelegation() {
    document.addEventListener(
        'submit',
        (event) => {
            const form = event.target;
            if (!(form instanceof HTMLFormElement)) {
                return;
            }
            const msg = form.getAttribute('data-confirm');
            if (!msg || msg === '') {
                return;
            }
            event.preventDefault();
            event.stopPropagation();

            showAppConfirm({
                message: msg,
                title: form.getAttribute('data-confirm-title') || '',
                confirmLabel: form.getAttribute('data-confirm-ok') || undefined,
                cancelLabel: form.getAttribute('data-confirm-cancel') || undefined,
            }).then((ok) => {
                if (ok) {
                    HTMLFormElement.prototype.submit.call(form);
                }
            });
        },
        true
    );
}

window.showAppConfirm = showAppConfirm;
window.showAppAlert = showAppAlert;

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', setupFormConfirmDelegation);
} else {
    setupFormConfirmDelegation();
}
