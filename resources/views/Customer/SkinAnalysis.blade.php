<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Skin Analysis 🌸') }}
        </h2>
    </x-slot>

    <div class="min-h-screen flex flex-col bg-pink-50">
        <div class="max-w-4xl mx-auto w-full px-6 py-10 flex-1">

            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Upload Card --}}
            <div class="bg-white rounded-2xl shadow-xl p-8 mb-6">
                <h3 class="text-2xl font-semibold text-center text-gray-700 mb-6">📸 Upload Your Skin Image</h3>

                <form id="skin-analysis-form" method="POST" enctype="multipart/form-data">
                    @csrf

                    {{-- Drag & Drop --}}
                    <div id="uploadArea" class="border-4 border-dashed border-purple-300 rounded-2xl p-16 text-center cursor-pointer transition-all hover:border-purple-400 hover:bg-purple-50">
                        <div class="text-6xl mb-4">🖼️</div>
                        <p class="text-gray-500 text-lg">Click to select or drag and drop your image here</p>
                        <p class="text-gray-400 text-sm">Supported: JPG, PNG, GIF (Max 2MB)</p>
                        <input type="file" id="fileInput" name="image" class="hidden" accept="image/*" required>
                    </div>

                    {{-- Preview --}}
                    <div id="previewContainer" class="hidden mt-6 text-center">
                        <img id="previewImage" src="" class="mx-auto rounded-xl shadow-lg mb-4 max-h-72">
                        <div class="bg-purple-50 p-4 rounded-lg mb-4">
                            <div id="fileName" class="font-semibold text-gray-700 break-all"></div>
                            <div id="fileSize" class="text-gray-500 text-sm"></div>
                        </div>

                        <div class="flex flex-col gap-4">
                            <button type="submit" id="uploadBtn" class="bg-purple-600 text-white py-3 rounded-lg font-semibold hover:bg-purple-700 transition-all">
                                📤 Analyze Skin
                            </button>
                            <button type="button" id="changeBtn" class="bg-purple-100 text-purple-600 py-3 rounded-lg font-semibold hover:bg-purple-200 transition-all">
                                🔄 Change Image
                            </button>
                        </div>

                        {{-- Loading --}}
                        <div id="loading" class="hidden mt-4">
                            <div class="spinner mx-auto mb-2"></div>
                            <p class="text-purple-600">Analyzing...</p>
                        </div>

                        {{-- Message --}}
                        <div id="message" class="hidden mt-4"></div>

                        {{-- Analysis Result --}}
                        <div id="analysisResultContainer" class="hidden mt-6">
                            <h4 class="text-xl font-semibold text-gray-700 mb-4"> Skin Analysis Result</h4>
                            <div id="skinTypeResult" class="text-center text-lg font-bold text-purple-600 mb-6"></div>

                            {{-- Products Grid --}}
                       <div id="recommendedProducts" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">

                                {{-- Default empty cards --}}
                                @for($i=0;$i<10;$i++)
                                    <div class="bg-white rounded-xl shadow p-4 flex items-center h-36">
                                        <p class="text-gray-400 italic text-center w-full">No product yet</p>
                                    </div>
                                @endfor
                            </div>
                        </div>
                    </div>
                </form>
            </div>

        </div>

        @include('layouts.footer')
    </div>

    {{-- Styles --}}
    <style>
        .upload-area.drag-over { border-color: #7c3aed !important; background: #f5f3ff !important; transform: scale(1.02); }
        .spinner { border: 4px solid #e5d9ff; border-top: 4px solid #7c3aed; border-radius: 50%; width: 40px; height: 40px; animation: spin 1s linear infinite; }
        @keyframes spin { 0% { transform: rotate(0deg);} 100% { transform: rotate(360deg);} }
        .message { padding: 15px; border-radius: 10px; }
        .message.success { background: #d4edda; color: #155724; }
        .message.error { background: #f8d7da; color: #721c24; }
    </style>

    {{-- Scripts --}}
    <script>
        let pauseFetching = false;

        const uploadArea = document.getElementById('uploadArea');
        const fileInput = document.getElementById('fileInput');
        const previewContainer = document.getElementById('previewContainer');
        const previewImage = document.getElementById('previewImage');
        const fileName = document.getElementById('fileName');
        const fileSize = document.getElementById('fileSize');
        const uploadBtn = document.getElementById('uploadBtn');
        const changeBtn = document.getElementById('changeBtn');
        const loading = document.getElementById('loading');
        const message = document.getElementById('message');
        const analysisResultContainer = document.getElementById('analysisResultContainer');
        const skinTypeResult = document.getElementById('skinTypeResult');
        const recommendedProducts = document.getElementById('recommendedProducts');

        // Drag & Drop
        uploadArea.addEventListener('click', () => fileInput.click());
        uploadArea.addEventListener('dragover', e => { e.preventDefault(); uploadArea.classList.add('drag-over'); });
        uploadArea.addEventListener('dragleave', () => uploadArea.classList.remove('drag-over'));
        uploadArea.addEventListener('drop', e => {
            e.preventDefault();
            uploadArea.classList.remove('drag-over');
            if(e.dataTransfer.files.length > 0) handleFile(e.dataTransfer.files[0]);
        });
        fileInput.addEventListener('change', e => { if(e.target.files.length > 0) handleFile(e.target.files[0]); });

        function handleFile(file){
            if(!file.type.startsWith('image/')) return showMessage("❌ Please select a valid image", "error");

            const reader = new FileReader();
            reader.onload = e => {
                previewImage.src = e.target.result;
                fileName.textContent = file.name;
                fileSize.textContent = (file.size/1024/1024).toFixed(2) + ' MB';
            };
            reader.readAsDataURL(file);

            uploadArea.classList.add("hidden");
            previewContainer.classList.remove("hidden");
            pauseFetching = true;
        }

        changeBtn.addEventListener("click", () => {
            pauseFetching = true;
            previewContainer.classList.add("hidden");
            uploadArea.classList.remove("hidden");
            fileInput.value = "";
            skinTypeResult.textContent = "";
            message.classList.add("hidden");
            analysisResultContainer.classList.add("hidden");

            recommendedProducts.innerHTML = '';
            for(let i=0;i<10;i++){
                recommendedProducts.innerHTML += `
                    <div class="bg-white rounded-xl shadow p-4 flex items-center h-36">
                        <p class="text-gray-400 italic text-center w-full">No product yet</p>
                    </div>
                `;
            }
        });

        function showMessage(text, type){
            message.innerHTML = text;
            message.className = "message " + type;
            message.classList.remove("hidden");
        }

        const skinForm = document.getElementById('skin-analysis-form');
        skinForm.addEventListener('submit', async e => {
            e.preventDefault();
            if(!fileInput.files[0]) return showMessage("❌ Please select an image first","error");

            const formData = new FormData();
            formData.append('image', fileInput.files[0]);

            uploadBtn.disabled = true;
            loading.classList.remove('hidden');
            message.classList.add('hidden');

            try {
                const response = await fetch('/api/upload-image', {
                    method:'POST',
                    headers:{ 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                    body: formData
                });

                const data = await response.json();

                if(data.success){
                    showMessage(`✅ ${data.message}`,'success');
                    analysisResultContainer.classList.remove('hidden');
                    pauseFetching = false;
                    setTimeout(fetchAnalysisResults, 1000);
                } else {
                    showMessage(`❌ ${data.message}`,'error');
                }

          } catch (err) {
    showMessage("❌ Upload failed. Check server logs.","error");
    console.error(err);
}
 finally {
                uploadBtn.disabled = false;
                loading.classList.add('hidden');
            }
        });

        async function fetchAnalysisResults() {
            if (pauseFetching) return;

            try {
                const res = await fetch('/api/get-received-data');
                const data = await res.json();

                skinTypeResult.textContent = '';
                recommendedProducts.innerHTML = '';

                if (!data.success || data.data.length === 0) {
                    for (let i = 0; i < 10; i++) {
                        recommendedProducts.innerHTML += `
                            <div class="bg-white rounded-xl shadow p-4 flex items-center h-36">
                                <p class="text-gray-400 italic text-center w-full">No product</p>
                            </div>
                        `;
                    }
                    return;
                }

                const sorted = data.data.sort((a, b) => b.id - a.id);
                const latestType = sorted[0].skin_type;
                skinTypeResult.textContent = `🧴 Skin Type: ${latestType}`;

                const products = sorted.filter(p => p.skin_type === latestType).slice(0, 10);

                for (let i = 0; i < 10; i++) {
                    const product = products[i];

                    if (product) {
                        let emoji = '🧴';
                        switch (product.label?.toLowerCase()) {
                            case 'face mask': emoji = '🧖‍♀️'; break;
                            case 'moisturizer': emoji = '💧'; break;
                            case 'herbal': emoji = '🌿'; break;
                            case 'sun protect': emoji = '🌞'; break;
                        }

                        recommendedProducts.innerHTML += `
                            <div class="bg-white rounded-xl shadow p-4 flex items-center gap-4 h-36 hover:scale-[1.02] transition">
                                <div class="text-4xl">${emoji}</div>
                                <div>
                                    <p class="font-semibold text-gray-700">${product.title}</p>
                                    <p class="text-gray-500 text-sm">${product.label}</p>
                                    <p class="text-gray-500 text-sm">${product.price}</p>
                                </div>
                            </div>
                        `;
                    } else {
                        recommendedProducts.innerHTML += `
                            <div class="bg-white rounded-xl shadow p-4 flex items-center h-36">
                                <p class="text-gray-400 italic text-center w-full">No product</p>
                            </div>
                        `;
                    }
                }

            } catch (err) {
                recommendedProducts.innerHTML = `
                    <div class="bg-red-100 text-red-600 rounded p-4 text-center col-span-2">
                        ${err.message}
                    </div>
                `;
            }
        }

        setInterval(fetchAnalysisResults, 2000);
    </script>
</x-app-layout>
