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
                    $partner = auth()->id() == $chat->student_id ? $chat->tutor->user : $chat->student;
                    $lastMessage = $chat->messages->first()?->message ?? __('Belum ada pesan');
                    $unread = $chat->unread_count;
                @endphp
                
                <a href="{{ route('chat.show', $chat) }}" 
                   data-chat-id="{{ $chat->id }}"
                   data-unread="{{ $unread }}"
                   data-last-message="{{ $lastMessage }}"
                   class="chat-item relative flex items-center gap-4 p-4 border-b transition {{ $unread > 0 ? 'bg-blue-50 hover:bg-blue-100' : 'hover:bg-gray-50' }}">
                   
                    <div class="blue-dot-wrapper">
                        @if($unread > 0)
                            <div class="w-2 h-2 rounded-full bg-blue-600 flex-shrink-0"></div>
                        @else
                            <div class="w-2 h-2"></div>
                        @endif
                    </div>

                    <div class="w-12 h-12 rounded-full bg-primary text-white flex items-center justify-center font-bold flex-shrink-0">
                        {{ strtoupper(substr($partner->name, 0, 1)) }}
                    </div>
                    
                    <div class="flex-1 min-w-0">
                        <h2 class="chat-name truncate {{ $unread > 0 ? 'font-bold text-gray-900' : 'font-semibold text-gray-800' }}">
                            {{ $partner->name }}
                        </h2>
                        <p class="chat-preview text-sm truncate {{ $unread > 0 ? 'font-semibold text-gray-900' : 'text-gray-500' }}">
                            {{ $lastMessage }}
                        </p>
                    </div>
                    
                    <div class="unread-wrapper ml-3">
                        @if($unread > 0)
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

    <div class="flex-1 flex flex-col items-center justify-center bg-gray-50">
        <div class="text-7xl mb-6">💬</div>
        <h2 class="text-2xl font-bold mb-2">{{ __('Selamat Datang di Messages') }}</h2>
        <p class="text-gray-500 text-center max-w-md">
            {{ __('Pilih salah satu percakapan di sebelah kiri') }}<br>{{ __('untuk mulai chatting dengan tutor.') }}
        </p>
    </div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        function syncSidebar(sidebarData) {
            const container = document.getElementById('conversationList');
            const emptyState = document.getElementById('emptyState');
            
            if (sidebarData.length > 0 && emptyState) {
                emptyState.remove();
            }

            for (let i = sidebarData.length - 1; i >= 0; i--) {
                const chat = sidebarData[i];
                let el = container.querySelector(`[data-chat-id="${chat.id}"]`);

                if (el) {
                    const currentUnread = parseInt(el.getAttribute('data-unread'));
                    const currentMessage = el.getAttribute('data-last-message');
                    let needsReposition = false;

                    if (currentUnread !== chat.unread || currentMessage !== chat.last_message) {
                        needsReposition = true;
                        
                        el.setAttribute('data-unread', chat.unread);
                        el.setAttribute('data-last-message', chat.last_message);
                        el.querySelector('.chat-preview').innerText = chat.last_message;

                        const nameEl = el.querySelector('.chat-name');
                        const previewEl = el.querySelector('.chat-preview');
                        const blueDotWrapper = el.querySelector('.blue-dot-wrapper');
                        const unreadWrapper = el.querySelector('.unread-wrapper');

                        el.className = 'chat-item relative flex items-center gap-4 p-4 border-b transition';

                        if (chat.unread > 0) {
                            el.classList.add('bg-blue-50', 'hover:bg-blue-100');
                            nameEl.className = 'chat-name truncate font-bold text-gray-900';
                            previewEl.className = 'chat-preview text-sm truncate font-semibold text-gray-900';
                            blueDotWrapper.innerHTML = `<div class="w-2 h-2 rounded-full bg-blue-600 flex-shrink-0"></div>`;
                            unreadWrapper.innerHTML = `<span class="min-w-6 h-6 px-2 flex items-center justify-center rounded-full bg-red-500 text-white text-xs font-bold">${chat.unread}</span>`;
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
                } else {
                    const newElHTML = `
                        <a href="/chat/${chat.id}" 
                           data-chat-id="${chat.id}"
                           data-unread="${chat.unread}"
                           data-last-message="${chat.last_message}"
                           class="chat-item relative flex items-center gap-4 p-4 border-b transition ${chat.unread > 0 ? 'bg-blue-50 hover:bg-blue-100' : 'hover:bg-gray-50'}">
                           
                            <div class="blue-dot-wrapper">
                                ${chat.unread > 0 ? '<div class="w-2 h-2 rounded-full bg-blue-600 flex-shrink-0"></div>' : '<div class="w-2 h-2"></div>'}
                            </div>

                            <div class="w-12 h-12 rounded-full bg-primary text-white flex items-center justify-center font-bold flex-shrink-0">
                                ${chat.initial}
                            </div>
                            
                            <div class="flex-1 min-w-0">
                                <h2 class="chat-name truncate ${chat.unread > 0 ? 'font-bold text-gray-900' : 'font-semibold text-gray-800'}">
                                    ${chat.partner}
                                </h2>
                                <p class="chat-preview text-sm truncate ${chat.unread > 0 ? 'font-semibold text-gray-900' : 'text-gray-500'}">
                                    ${chat.last_message}
                                </p>
                            </div>
                            
                            <div class="unread-wrapper ml-3">
                                ${chat.unread > 0 ? `<span class="min-w-6 h-6 px-2 flex items-center justify-center rounded-full bg-red-500 text-white text-xs font-bold">${chat.unread}</span>` : ''}
                            </div>
                        </a>
                    `;
                    
                    const tempDiv = document.createElement('div');
                    tempDiv.innerHTML = newElHTML.trim();
                    container.prepend(tempDiv.firstChild);
                }
            }
        }

        async function fetchSidebar(){
            try {
                const response = await fetch("{{ route('chat.sidebar') }}", {
                    headers: { "Accept": "application/json" }
                });

                if (!response.ok) return;

                const data = await response.json();
                
                syncSidebar(data);

            } catch (error) {
                console.error(error);
            }
        }

        setInterval(fetchSidebar, 2000);
    });
</script>
@endsection