import axios from 'axios';

const appEl = document.getElementById('chat-app');

if (!appEl) {
    throw new Error('Chat root element not found.');
}

const authId = Number(appEl.dataset.authId);
const authName = appEl.dataset.authName ?? 'Me';
const users = JSON.parse(appEl.dataset.users ?? '[]');

const sidebarEl = document.getElementById('chat-sidebar');
const overlayEl = document.getElementById('mobile-overlay');
const sidebarOpenBtn = document.getElementById('sidebar-open');
const sidebarCloseBtn = document.getElementById('sidebar-close');
const userListEl = document.getElementById('user-list');
const userSearchEl = document.getElementById('user-search');
const chatTitleEl = document.getElementById('chat-title');
const chatAvatarLetterEl = document.getElementById('chat-avatar-letter');
const chatStatusDotEl = document.getElementById('chat-status-dot');
const chatStatusTextEl = document.getElementById('chat-status-text');
const messageListEl = document.getElementById('message-list');
const messageScrollEl = document.getElementById('message-scroll');
const emptyStateEl = document.getElementById('chat-empty-state');
const messageFormEl = document.getElementById('message-form');
const messageInputEl = document.getElementById('message-input');
const sendButtonEl = document.getElementById('send-button');
const chatHintEl = document.getElementById('chat-hint');
const reverbConnDotEl = document.getElementById('reverb-conn-dot');
const reverbConnTextEl = document.getElementById('reverb-conn-text');

const onlineUserIds = new Set();
const unreadCounts = new Map();

let activeUserId = null;
let activeUser = null;
let lastLoadedForUserId = null;
let joinedOnline = false;

function isMobile() {
    return window.matchMedia('(max-width: 1023px)').matches;
}

function setReverbConnection(state) {
    if (!reverbConnDotEl || !reverbConnTextEl) return;

    reverbConnDotEl.classList.remove('bg-emerald-400', 'bg-amber-300', 'bg-slate-400/70');
    reverbConnTextEl.textContent = state;

    if (state === 'Connected') {
        reverbConnDotEl.classList.add('bg-emerald-400');
        return;
    }

    if (state === 'Connecting') {
        reverbConnDotEl.classList.add('bg-amber-300');
        return;
    }

    reverbConnDotEl.classList.add('bg-slate-400/70');
}

function openSidebar() {
    if (!sidebarEl || !overlayEl) return;

    sidebarEl.classList.remove('hidden');
    sidebarEl.classList.remove('-translate-x-full');
    sidebarEl.classList.add('translate-x-0');
    overlayEl.classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
}

function closeSidebar() {
    if (!sidebarEl || !overlayEl) return;

    sidebarEl.classList.add('-translate-x-full');
    sidebarEl.classList.remove('translate-x-0');
    overlayEl.classList.add('hidden');
    document.body.classList.remove('overflow-hidden');

    const onEnd = () => {
        if (sidebarEl.classList.contains('-translate-x-full')) {
            sidebarEl.classList.add('hidden');
        }
        sidebarEl.removeEventListener('transitionend', onEnd);
    };
    sidebarEl.addEventListener('transitionend', onEnd);
}

function escapeHtml(input) {
    const div = document.createElement('div');
    div.textContent = input ?? '';
    return div.innerHTML;
}

function formatTime(iso) {
    const date = new Date(iso);
    if (Number.isNaN(date.getTime())) return '';
    return new Intl.DateTimeFormat('id-ID', {
        hour: '2-digit',
        minute: '2-digit',
        hour12: false,
        timeZone: 'Asia/Jakarta',
    }).format(date);
}

function setActiveUser(user) {
    activeUser = user;
    activeUserId = user?.id ?? null;

    Array.from(userListEl.querySelectorAll('[data-user-id]')).forEach((el) => {
        const isActive = Number(el.dataset.userId) === activeUserId;
        el.classList.toggle('ring-2', isActive);
        el.classList.toggle('ring-blue-500/60', isActive);
        el.classList.toggle('bg-blue-50/70', isActive);
        el.classList.toggle('bg-white/80', isActive);
    });

    if (!activeUserId) {
        chatTitleEl.textContent = 'Pilih user';
        if (chatAvatarLetterEl) chatAvatarLetterEl.textContent = '?';
        setChatStatus(false);
        messageInputEl.disabled = true;
        sendButtonEl.disabled = true;
        if (emptyStateEl) emptyStateEl.classList.remove('hidden');
        chatHintEl.textContent = 'Pilih user di sidebar untuk mulai chat.';
        return;
    }

    unreadCounts.delete(activeUserId);
    renderUserList(userSearchEl?.value);

    chatTitleEl.textContent = activeUser.name;
    if (chatAvatarLetterEl) chatAvatarLetterEl.textContent = (activeUser.name ?? '?').trim().charAt(0).toUpperCase();
    setChatStatus(onlineUserIds.has(activeUserId));
    messageInputEl.disabled = false;
    sendButtonEl.disabled = false;
    if (emptyStateEl) emptyStateEl.classList.add('hidden');
    chatHintEl.textContent = 'Tekan Enter untuk kirim, Shift+Enter untuk baris baru.';

    loadMessages(activeUserId);

    if (isMobile()) {
        closeSidebar();
    }
}

function setChatStatus(isOnline) {
    chatStatusDotEl.classList.toggle('bg-emerald-400', isOnline);
    chatStatusDotEl.classList.toggle('bg-slate-400/70', !isOnline);
    chatStatusTextEl.textContent = isOnline ? 'Online' : 'Offline';
    chatStatusTextEl.classList.remove('text-emerald-200', 'text-white/70');
    chatStatusTextEl.classList.add(isOnline ? 'text-emerald-200' : 'text-white/70');
}

function renderUserItem(user) {
    const isOnline = onlineUserIds.has(user.id);
    const unread = unreadCounts.get(user.id) ?? 0;

    const wrapper = document.createElement('button');
    wrapper.type = 'button';
    wrapper.dataset.userId = String(user.id);
    wrapper.className =
        'group flex w-full items-center gap-3 rounded-[20px] px-4 py-3 text-left transition hover:translate-y-[-1px] hover:bg-white hover:shadow-md hover:shadow-blue-900/5 focus:outline-none';

    const avatar = document.createElement('div');
    avatar.className =
        'relative grid h-12 w-12 shrink-0 place-items-center rounded-full bg-gradient-to-br from-[#3b82f6] to-[#1e40af] shadow-sm';

    const letter = document.createElement('span');
    letter.className = 'text-sm font-extrabold tracking-wide text-white';
    letter.textContent = (user.name ?? '?').trim().charAt(0).toUpperCase();
    avatar.appendChild(letter);

    const status = document.createElement('span');
    status.className =
        'absolute -bottom-0.5 -right-0.5 h-3.5 w-3.5 rounded-full border-2 border-white ' +
        (isOnline ? 'bg-emerald-400' : 'bg-slate-300');
    avatar.appendChild(status);

    const meta = document.createElement('div');
    meta.className = 'min-w-0 flex-1';

    const name = document.createElement('div');
    name.className = 'truncate text-sm font-semibold text-slate-900';
    name.textContent = user.name;

    const sub = document.createElement('div');
    sub.className = 'truncate text-xs font-semibold ' + (isOnline ? 'text-emerald-600' : 'text-slate-500');
    sub.textContent = isOnline ? 'Online' : 'Offline';

    meta.appendChild(name);
    meta.appendChild(sub);

    const right = document.createElement('div');
    right.className = 'shrink-0';

    if (unread > 0) {
        const badge = document.createElement('div');
        badge.className =
            'grid min-w-[26px] place-items-center rounded-full bg-gradient-to-br from-[#3b82f6] to-[#1e40af] px-2 py-1 text-[11px] font-extrabold text-white shadow-sm';
        badge.textContent = unread > 99 ? '99+' : String(unread);
        right.appendChild(badge);
    }

    wrapper.appendChild(avatar);
    wrapper.appendChild(meta);
    wrapper.appendChild(right);

    wrapper.addEventListener('click', () => setActiveUser(user));

    return wrapper;
}

function renderUserList(filterText = '') {
    userListEl.innerHTML = '';

    const normalized = (filterText ?? '').toLowerCase().trim();
    const filtered = normalized
        ? users.filter((u) => u.name.toLowerCase().includes(normalized) || u.email.toLowerCase().includes(normalized))
        : users;

    if (filtered.length === 0) {
        const empty = document.createElement('div');
        empty.className = 'px-4 py-10 text-center text-sm text-slate-500';
        empty.textContent = 'User tidak ditemukan.';
        userListEl.appendChild(empty);
        return;
    }

    filtered.forEach((user) => userListEl.appendChild(renderUserItem(user)));

    if (activeUserId) {
        const activeBtn = userListEl.querySelector(`[data-user-id="${activeUserId}"]`);
        activeBtn?.classList.add('ring-2', 'ring-blue-500/60', 'bg-blue-50/70', 'bg-white/80');
    }
}

function renderMessageBubble(message) {
    const mine = Number(message.sender_id) === authId;
    const row = document.createElement('div');
    row.className = 'flex animate-message-in';
    row.classList.add(mine ? 'justify-end' : 'justify-start');

    const bubble = document.createElement('div');
    bubble.className =
        'max-w-[88%] rounded-[20px] px-5 py-3 shadow-sm sm:max-w-[72%] ' +
        (mine
            ? 'bg-gradient-to-br from-[#3b82f6] to-[#1e40af] text-white shadow-blue-900/15'
            : 'bg-white text-slate-900 ring-1 ring-slate-200');

    const text = document.createElement('div');
    text.className = 'whitespace-pre-wrap break-words text-sm leading-relaxed';
    text.innerHTML = escapeHtml(message.message);

    const meta = document.createElement('div');
    meta.className = mine ? 'mt-2 text-right text-[11px] text-white/80' : 'mt-2 text-right text-[11px] font-medium text-slate-500';
    meta.textContent = formatTime(message.created_at);

    bubble.appendChild(text);
    bubble.appendChild(meta);
    row.appendChild(bubble);

    return row;
}

function scrollToBottom(behavior = 'smooth') {
    messageScrollEl.scrollTo({
        top: messageScrollEl.scrollHeight,
        behavior,
    });
}

async function loadMessages(userId) {
    if (!userId) return;

    lastLoadedForUserId = userId;
    messageListEl.innerHTML = '';

    const { data } = await axios.get(`/chat/messages/${userId}`);

    if (lastLoadedForUserId !== userId) return;

    (data.messages ?? []).forEach((m) => messageListEl.appendChild(renderMessageBubble(m)));
    if ((data.messages ?? []).length > 0) {
        if (emptyStateEl) emptyStateEl.classList.add('hidden');
    }
    scrollToBottom('auto');
}

function appendMessage(message) {
    messageListEl.appendChild(renderMessageBubble(message));
    scrollToBottom();
}

function getSocketHeaders() {
    if (typeof window.Echo === 'undefined') return {};
    const socketId = window.Echo.socketId?.();
    return socketId ? { 'X-Socket-ID': socketId } : {};
}

async function sendMessage(text) {
    if (!activeUserId) return;

    const { data } = await axios.post(
        `/chat/messages/${activeUserId}`,
        { message: text },
        { headers: getSocketHeaders() }
    );
    appendMessage(data.message);
}

function handleIncomingMessage(payload) {
    const message = payload?.message ?? payload;
    if (!message?.sender_id || !message?.receiver_id) return;

    const senderId = Number(message.sender_id);
    const receiverId = Number(message.receiver_id);
    const otherUserId = senderId === authId ? receiverId : senderId;

    const belongsToActive =
        activeUserId &&
        ((senderId === authId && receiverId === activeUserId) || (senderId === activeUserId && receiverId === authId));

    if (belongsToActive) {
        appendMessage(message);
        return;
    }

    if (otherUserId && otherUserId !== authId) {
        unreadCounts.set(otherUserId, (unreadCounts.get(otherUserId) ?? 0) + 1);
        renderUserList(userSearchEl?.value);
    }
}

function enableTextareaAutoResize() {
    const resize = () => {
        messageInputEl.style.height = 'auto';
        messageInputEl.style.height = `${Math.min(messageInputEl.scrollHeight, 128)}px`;
    };
    messageInputEl.addEventListener('input', resize);
    resize();
}

sidebarOpenBtn?.addEventListener('click', openSidebar);
sidebarCloseBtn?.addEventListener('click', closeSidebar);
overlayEl?.addEventListener('click', closeSidebar);

userSearchEl?.addEventListener('input', (e) => renderUserList(e.target.value));

messageInputEl?.addEventListener('keydown', (e) => {
    if (e.key !== 'Enter' || e.shiftKey) return;
    e.preventDefault();
    messageFormEl.requestSubmit();
});

messageFormEl?.addEventListener('submit', async (e) => {
    e.preventDefault();
    if (!activeUserId) return;

    const text = (messageInputEl.value ?? '').trim();
    if (!text) return;

    messageInputEl.value = '';
    messageInputEl.dispatchEvent(new Event('input'));

    await sendMessage(text);
});

renderUserList();
enableTextareaAutoResize();
setActiveUser(null);
setReverbConnection('Disconnected');

if (typeof window.Echo !== 'undefined') {
    window.Echo.private(`chat.${authId}`).listen('.message.sent', (e) => handleIncomingMessage(e));

    const joinOnline = () => {
        if (joinedOnline) return;
        joinedOnline = true;

        window.Echo.join('online')
            .here((members) => {
                onlineUserIds.clear();
                members.forEach((m) => onlineUserIds.add(Number(m.id)));
                renderUserList(userSearchEl.value);
                if (activeUserId) setChatStatus(onlineUserIds.has(activeUserId));
            })
            .joining((member) => {
                onlineUserIds.add(Number(member.id));
                renderUserList(userSearchEl.value);
                if (activeUserId) setChatStatus(onlineUserIds.has(activeUserId));
            })
            .leaving((member) => {
                onlineUserIds.delete(Number(member.id));
                renderUserList(userSearchEl.value);
                if (activeUserId) setChatStatus(onlineUserIds.has(activeUserId));
            });
    };

    const conn = window.Echo.connector?.pusher?.connection;
    if (conn) {
        setReverbConnection(conn.state === 'connected' ? 'Connected' : 'Connecting');

        conn.bind('connected', () => {
            setReverbConnection('Connected');
            joinOnline();
        });

        conn.bind('disconnected', () => {
            setReverbConnection('Disconnected');
            onlineUserIds.clear();
            renderUserList(userSearchEl.value);
            if (activeUserId) setChatStatus(false);
            joinedOnline = false;
        });

        conn.bind('error', () => {
            setReverbConnection('Disconnected');
        });

        conn.bind('state_change', ({ current }) => {
            if (current === 'connecting' || current === 'initialized') setReverbConnection('Connecting');
            if (current === 'connected') setReverbConnection('Connected');
            if (current === 'disconnected' || current === 'unavailable' || current === 'failed') setReverbConnection('Disconnected');
        });
    }

    joinOnline();
}

window.addEventListener('resize', () => {
    if (!isMobile()) closeSidebar();
});
