const room = window.room;
const user = window.user;

const messagesDiv = document.getElementById('messages');
const memberList = document.getElementById('memberList');
const input = document.getElementById('input');
const sendBtn = document.getElementById('send');
const channelListDiv = document.getElementById('channelList');

let currentChannel = 'general';

// Send message
sendBtn.addEventListener('click', sendMessage);
input.addEventListener('keypress', e => { if (e.key === 'Enter') sendMessage(); });

// Load channels on start
loadChannels();

// Polling
setInterval(loadMessages, 1000);
setInterval(loadMembers, 2000);
fetch(`api/get_members.php?room=${room}&user=${user}`);

function sendMessage() {
    const msg = input.value.trim();
    if (!msg) return;
    fetch(`api/send_message.php?room=${room}&channel=${currentChannel}&user=${encodeURIComponent(user)}&text=${encodeURIComponent(msg)}`)
    .then(() => loadMessages());
    input.value = '';
}
sendMessage();

function loadMessages() {
    fetch(`api/get_messages.php?room=${room}&channel=${currentChannel}`)
    .then(res => res.json())
    .then(data => {
        messagesDiv.innerHTML = '';
        data.forEach(m => {
            const div = document.createElement('div');
            div.className = 'message';
            const nameEl = document.createElement('div');
            nameEl.className = 'username';
            nameEl.textContent = m.user;
            div.appendChild(nameEl);

            // Detect links
            const linkRegex = /(https?:\/\/[^\s]+)/g;
            const parts = m.text.split(linkRegex);
            parts.forEach(p => {
                if (linkRegex.test(p)) {
                    const a = document.createElement('a');
                    a.href = p;
                    a.textContent = p;
                    a.target = '_blank';
                    div.appendChild(a);
                    const copyBtn = document.createElement('span');
                    copyBtn.className = 'link-btn';
                    copyBtn.textContent = ' [Copy]';
                    copyBtn.onclick = () => navigator.clipboard.writeText(p);
                    div.appendChild(copyBtn);
                } else {
                    div.appendChild(document.createTextNode(p));
                }
            });

            messagesDiv.appendChild(div);
        });
        messagesDiv.scrollTop = messagesDiv.scrollHeight;
    });
}
loadMessages();

function loadMembers() {
    fetch(`api/get_members.php?room=${room}`)
    .then(res => res.json())
    .then(data => {
        memberList.innerHTML = '<strong>Members</strong>';
        data.forEach(u => {
            const div = document.createElement('div');
            div.className = 'member';
            div.textContent = u;
            memberList.appendChild(div);
        });
    });
}
loadMembers();
function loadChannels() {
    fetch(`api/get_channels.php?room=${room}`)
    .then(res => res.json())
    .then(channels => {
        channelListDiv.innerHTML = '<strong>Channels</strong><br>';
        channels.forEach(ch => {
            const btn = document.createElement('button');
            btn.textContent = ch;
            btn.onclick = () => {
                currentChannel = ch;
                loadMessages();
            };
            channelListDiv.appendChild(btn);
        });
        const createBtn = document.createElement('button');
        createBtn.textContent = '+ Add Channel';
        createBtn.onclick = () => {
            const name = prompt("New channel name:");
            if (name) fetch(`api/create_channel.php?room=${room}&channel=${encodeURIComponent(name)}`).then(() => loadChannels());
        };
        channelListDiv.appendChild(createBtn);
    });
}
loadChannels();