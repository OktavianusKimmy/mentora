@extends('layouts.app')

@section('content')
<div class="h-full bg-white rounded-2xl shadow overflow-hidden flex">

    <div class="w-96 border-r flex flex-col flex-shrink-0">
        <div class="p-6 border-b">
            <h1 class="text-2xl font-bold">{{ __('Pesan') }}</h1>
            <p class="text-gray-500 text-sm mt-1">{{ __('Chat dengan tutor Mentora') }}</p>
        </div>

        <div class="p-4 border-b">
            <input type="text" placeholder="{{ __('Cari percakapan...') }}" class="w-full border rounded-xl px-4 py-2 focus:ring-primary focus:border-primary">
        </div>

        <div id="conversationList" class="flex-1 overflow-y-auto">
            @forelse($conversations as $chat)
                @php
                    $partnerList = auth()->id() == $chat->student_id ? $chat->tutor->user : $chat->student;
                    $lastMessageList = $chat->messages->sortByDesc('created_at')->first()?->message ?? __('Belum ada pesan');
                    $unread = $chat->unread_count;
                    $isActive = $chat->id == $conversation->id;
                @endphp

                <a href="{{ route('chat.show', $chat) }}" 
                   data-chat-id="{{ $chat->id }}"
                   data-unread="{{ $isActive ? 0 : $unread }}"
                   data-last-message="{{ $lastMessageList }}"
                   class="chat-item flex items-center gap-4 p-4 border-b transition {{ $isActive ? 'bg-primary/10' : ($unread > 0 ? 'bg-blue-50 hover:bg-blue-100' : 'hover:bg-gray-50') }}">
                    
                    <div class="blue-dot-wrapper">
                        @if(!$isActive && $unread > 0)
                            <div class="w-2 h-2 rounded-full bg-blue-600 flex-shrink-0"></div>
                        @else
                            <div class="w-2 h-2"></div>
                        @endif
                    </div>

                    <div class="w-12 h-12 rounded-full bg-primary text-white flex items-center justify-center font-bold flex-shrink-0">
                        {{ strtoupper(substr($partnerList->name, 0, 1)) }}
                    </div>
                    
                    <div class="flex-1 min-w-0">
                        <h2 class="chat-name truncate {{ (!$isActive && $unread > 0) ? 'font-bold text-gray-900' : 'font-semibold text-gray-800' }}">
                            {{ $partnerList->name }}
                        </h2>
                        <p class="chat-preview text-sm truncate {{ (!$isActive && $unread > 0) ? 'font-semibold text-gray-900' : 'text-gray-500' }}">
                            {{ $lastMessageList }}
                        </p>
                    </div>

                    <div class="unread-wrapper ml-3">
                        @if(!$isActive && $unread > 0)
                            <span class="min-w-6 h-6 px-2 flex items-center justify-center rounded-full bg-red-500 text-white text-xs font-bold">
                                {{ $unread }}
                            </span>
                        @endif
                    </div>
                </a>
            @empty
                <div id="emptyState" class="h-full flex items-center justify-center text-gray-400">
                    {{ __('Belum ada percakapan') }}
                </div>
            @endforelse
        </div>
    </div>

    <div class="flex-1 flex flex-col">
        @php
            $partner = auth()->id() == $conversation->student_id ? $conversation->tutor->user : $conversation->student;
        @endphp

        <div class="p-6 border-b flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-primary text-white flex items-center justify-center font-bold flex-shrink-0">
                {{ strtoupper(substr($partner->name, 0, 1)) }}
            </div>
            <div>
                <h2 class="font-bold text-lg">{{ $partner->name }}</h2>
                <p class="text-green-500 text-sm">● {{ __('Online') }}</p>
            </div>
        </div>

        <div id="messages" class="flex-1 overflow-y-auto bg-gray-50 p-6 space-y-4">
            @forelse($conversation->messages as $message)
                <div id="message-{{ $message->id }}" class="{{ $message->sender_id == auth()->id() ? 'text-right' : '' }}">
                    <div class="inline-block max-w-xl px-4 py-3 rounded-2xl shadow-sm text-left {{ $message->sender_id == auth()->id() ? 'bg-primary text-white' : 'bg-white' }}">
                        <p>{{ $message->message }}</p>
                        <p class="text-[10px] opacity-70 mt-2 {{ $message->sender_id == auth()->id() ? 'text-right' : 'text-left' }}">
                            {{ $message->created_at->format('H:i') }}
                        </p>
                    </div>
                </div>
            @empty
                <div class="h-full flex items-center justify-center text-gray-400">
                    {{ __('Belum ada pesan') }}
                </div>
            @endforelse
        </div>

        <form id="chatForm" action="{{ route('chat.send', $conversation) }}" method="POST" class="border-t p-4 flex gap-3">
            @csrf
            <input id="messageInput" type="text" name="message" placeholder="{{ __('Ketik pesan...') }}" autocomplete="off" required class="flex-1 border rounded-xl px-4 py-3 focus:ring-primary focus:border-primary">
            <button type="submit" class="bg-primary text-white px-6 rounded-xl hover:opacity-90 transition">
                {{ __('Kirim') }}
            </button>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const messages = document.getElementById('messages');
        const form = document.getElementById('chatForm');
        const input = document.getElementById('messageInput');
        let lastMessageId = {{ $conversation->messages->max('id') ?? 0 }};
        const currentChatId = {{ $conversation->id }};
        
        messages.scrollTop = messages.scrollHeight;

        function appendMessage(message) {
            if(document.getElementById('message-' + message.id)){
                return;
            }
            const mine = message.sender_id == {{ auth()->id() }};
            const time = new Date(message.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

            messages.insertAdjacentHTML('beforeend', `
                <div id="message-${message.id}" class="${mine ? 'text-right' : ''}">
                    <div class="inline-block max-w-xl px-4 py-3 rounded-2xl shadow-sm text-left ${mine ? 'bg-primary text-white' : 'bg-white'}">
                        <p>${message.message}</p>
                        <p class="text-[10px] opacity-70 mt-2 ${mine ? 'text-right' : ''}">${time}</p>
                    </div>
                </div>
            `);
            messages.scrollTop = messages.scrollHeight;
        }

        function syncSidebar(sidebarData) {
            const container = document.getElementById('conversationList');
            const emptyState = document.getElementById('emptyState');
            if (sidebarData.length > 0 && emptyState) emptyState.remove();

            for (let i = sidebarData.length - 1; i >= 0; i--) {
                const chat = sidebarData[i];
                let el = container.querySelector(`[data-chat-id="${chat.id}"]`);
                const isActive = chat.id == currentChatId;

                if (el) {
                    const currentUnread = parseInt(el.getAttribute('data-unread'));
                    const currentMessage = el.getAttribute('data-last-message');
                    let needsReposition = false;
                    const actualUnread = isActive ? 0 : chat.unread;

                    if (currentUnread !== actualUnread || currentMessage !== chat.last_message) {
                        needsReposition = true;
                        
                        el.setAttribute('data-unread', actualUnread);
                        el.setAttribute('data-last-message', chat.last_message);
                        el.querySelector('.chat-preview').innerText = chat.last_message;

                        const nameEl = el.querySelector('.chat-name');
                        const previewEl = el.querySelector('.chat-preview');
                        const blueDotWrapper = el.querySelector('.blue-dot-wrapper');
                        const unreadWrapper = el.querySelector('.unread-wrapper');

                        el.className = `chat-item flex items-center gap-4 p-4 border-b transition`;

                        if (isActive) {
                            el.classList.add('bg-primary/10');
                            nameEl.className = 'chat-name truncate font-semibold text-gray-800';
                            previewEl.className = 'chat-preview text-sm truncate text-gray-500';
                            blueDotWrapper.innerHTML = `<div class="w-2 h-2"></div>`;
                            unreadWrapper.innerHTML = ``;
                        } else if (actualUnread > 0) {
                            el.classList.add('bg-blue-50', 'hover:bg-blue-100');
                            nameEl.className = 'chat-name truncate font-bold text-gray-900';
                            previewEl.className = 'chat-preview text-sm truncate font-semibold text-gray-900';
                            blueDotWrapper.innerHTML = `<div class="w-2 h-2 rounded-full bg-blue-600 flex-shrink-0"></div>`;
                            unreadWrapper.innerHTML = `<span class="min-w-6 h-6 px-2 flex items-center justify-center rounded-full bg-red-500 text-white text-xs font-bold">${actualUnread}</span>`;
                        } else {
                            el.classList.add('hover:bg-gray-50');
                            nameEl.className = 'chat-name truncate font-semibold text-gray-800';
                            previewEl.className = 'chat-preview text-sm truncate text-gray-500';
                            blueDotWrapper.innerHTML = `<div class="w-2 h-2"></div>`;
                            unreadWrapper.innerHTML = ``;
                        }
                    }

                    if (needsReposition || container.children[i] !== el) {
                        container.prepend(el);
                    }
                }
            }
        }

        async function fetchNewMessages(){
            try{
                const response = await fetch("{{ route('chat.messages', $conversation) }}?last_id=" + lastMessageId, {
                    headers: { "Accept": "application/json" }
                });

                if(!response.ok) return;

                const data = await response.json();

                if (data.messages && data.messages.length > 0) {
                    data.messages.forEach(msg => {
                        appendMessage(msg);
                        lastMessageId = msg.id;
                    });
                }

                if (data.sidebar) {
                    syncSidebar(data.sidebar);
                }

            }catch(error){
                console.error(error);
            }
        }

        if(form){
            form.addEventListener('submit', async function(e){
                e.preventDefault();
                const text = input.value.trim();
                if(text === '') return;
                
                const formData = new FormData(form);

                const activeSidebarEl = document.querySelector(`[data-chat-id="${currentChatId}"]`);
                if (activeSidebarEl) {
                    activeSidebarEl.setAttribute('data-last-message', text);
                    activeSidebarEl.querySelector('.chat-preview').innerText = text;
                    document.getElementById('conversationList').prepend(activeSidebarEl);
                }

                const tempId = Date.now(); 
                const timeNow = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                messages.insertAdjacentHTML('beforeend', `
                    <div id="message-temp-${tempId}" class="text-right opacity-70">
                        <div class="inline-block max-w-xl px-4 py-3 rounded-2xl shadow-sm text-left bg-primary text-white">
                            <p>${text}</p>
                            <p class="text-[10px] mt-2 text-right">${timeNow} (Mengirim...)</p>
                        </div>
                    </div>
                `);
                messages.scrollTop = messages.scrollHeight;

                input.value = '';
                input.focus();

                try{
                    const response = await fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        },
                        body: formData
                    });

                    if(!response.ok) throw new Error("Gagal mengirim pesan");

                    const data = await response.json();

                    document.getElementById(`message-temp-${tempId}`).remove();

                    appendMessage(data);
                    lastMessageId = Math.max(lastMessageId, data.id);
                    
                    fetchNewMessages();

                }catch(error){
                    console.error(error);
                    const tempBubble = document.getElementById(`message-temp-${tempId}`);
                    if(tempBubble) tempBubble.querySelector('p:last-child').innerText = 'Gagal dikirim';
                }
            });
        }

        input.addEventListener('keydown', function(e){
            if(e.key === 'Enter' && !e.shiftKey){
                e.preventDefault();
                form.requestSubmit();
            }
        });

        setInterval(fetchNewMessages, 2000);
    });
</script>
@endsection