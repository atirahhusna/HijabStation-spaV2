<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Skin Analysis 🌸') }}
        </h2>
    </x-slot>

    <div class="min-h-screen flex flex-col bg-pink-50">
        <div class="max-w-4xl mx-auto w-full px-6 py-10 flex-1">

            {{-- Upload Card --}}
            <div class="bg-white rounded-2xl shadow-xl p-8 mb-6">
                <h3 class="text-2xl font-semibold text-center text-gray-700 mb-6">
                    📸 Upload Your Skin Image
                </h3>

                <form id="skin-analysis-form" method="POST" enctype="multipart/form-data">
                    @csrf

                    {{-- Upload Area --}}
                    <div id="uploadArea"
                        class="border-4 border-dashed border-purple-300 rounded-2xl p-16 text-center cursor-pointer hover:border-purple-400 hover:bg-purple-50 transition">
                        <div class="text-6xl mb-4">🖼️</div>
                        <p class="text-gray-500">Click or drag image here</p>
                        <input type="file" id="fileInput" class="hidden" accept="image/*" required>
                    </div>

                    {{-- Preview --}}
                    <div id="previewContainer" class="hidden mt-6 text-center">
                        <img id="previewImage" class="mx-auto rounded-xl shadow max-h-72 mb-4">

                        <div class="flex flex-col gap-3">
                            <button type="submit" id="uploadBtn"
                                class="bg-purple-600 text-white py-3 rounded-lg font-semibold hover:bg-purple-700">
                                📤 Analyze Skin
                            </button>

                            <button type="button" id="changeBtn"
                                class="bg-purple-100 text-purple-600 py-3 rounded-lg font-semibold">
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

                        {{-- Result --}}
                        <div id="analysisResultContainer" class="hidden mt-8">
                            <h4 class="text-xl font-semibold mb-2">Skin Analysis Result</h4>
                            <div id="skinTypeResult"
                                class="text-lg font-bold text-purple-600 mb-6"></div>

                            {{-- TABLE OUTPUT --}}
                            <div id="recommendedProducts" class="space-y-8"></div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        @include('layouts.footer')
    </div>

    {{-- STYLES --}}
    <style>
        .spinner {
            border: 4px solid #e5d9ff;
            border-top: 4px solid #7c3aed;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            100% { transform: rotate(360deg); }
        }

        .message {
            padding: 12px;
            border-radius: 8px;
        }

        .message.success {
            background: #dcfce7;
            color: #166534;
        }

        .message.error {
            background: #fee2e2;
            color: #991b1b;
        }

        .product-table {
            width: 100%;
            border-collapse: collapse;
        }

        .product-table th,
        .product-table td {
            padding: 10px;
            border-bottom: 1px solid #e5e7eb;
            vertical-align: top;
        }

        .product-table th {
            background: #f3e8ff;
            color: #6b21a8;
            font-weight: 600;
        }

        .product-name {
            max-width: 260px;
            word-break: break-word;
            white-space: normal;
        }

        .label-title {
            font-size: 1.2rem;
            font-weight: 700;
            color: #7c3aed;
            margin-bottom: 10px;
        }
    </style>

    {{-- SCRIPT --}}
    <script>
        let pauseFetching = false;

        const uploadArea = document.getElementById('uploadArea');
        const fileInput = document.getElementById('fileInput');
        const previewContainer = document.getElementById('previewContainer');
        const previewImage = document.getElementById('previewImage');
        const uploadBtn = document.getElementById('uploadBtn');
        const changeBtn = document.getElementById('changeBtn');
        const loading = document.getElementById('loading');
        const message = document.getElementById('message');
        const analysisResultContainer = document.getElementById('analysisResultContainer');
        const skinTypeResult = document.getElementById('skinTypeResult');
        const recommendedProducts = document.getElementById('recommendedProducts');

        uploadArea.onclick = () => fileInput.click();

        fileInput.onchange = e => {
            const file = e.target.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = e => previewImage.src = e.target.result;
            reader.readAsDataURL(file);

            uploadArea.classList.add('hidden');
            previewContainer.classList.remove('hidden');
        };

        changeBtn.onclick = () => {
            pauseFetching = true;
            previewContainer.classList.add('hidden');
            uploadArea.classList.remove('hidden');
            fileInput.value = '';
            analysisResultContainer.classList.add('hidden');
        };

        function showMessage(text, type) {
            message.textContent = text;
            message.className = `message ${type}`;
            message.classList.remove('hidden');
        }

        document.getElementById('skin-analysis-form').onsubmit = async e => {
            e.preventDefault();

            const formData = new FormData();
            formData.append('image', fileInput.files[0]);

            uploadBtn.disabled = true;
            loading.classList.remove('hidden');

            const res = await fetch('/api/upload-image', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                body: formData
            });

            const data = await res.json();
            uploadBtn.disabled = false;
            loading.classList.add('hidden');

            if (data.success) {
                showMessage('Image uploaded successfully', 'success');
                analysisResultContainer.classList.remove('hidden');
                pauseFetching = false;
                fetchAnalysisResults();
            } else {
                showMessage('Upload failed', 'error');
            }
        };

        async function fetchAnalysisResults() {
            if (pauseFetching) return;

            const res = await fetch('/api/get-received-data');
            const data = await res.json();

            if (!data.success || data.data.length === 0) return;

            const latestType = data.data[0].skin_type;
            skinTypeResult.textContent = `🧴 Skin Type: ${latestType}`;

            const products = data.data.filter(p => p.skin_type === latestType);
            const grouped = {};

            products.forEach(p => {
                if (!grouped[p.label]) grouped[p.label] = [];
                grouped[p.label].push(p);
            });

            recommendedProducts.innerHTML = '';

            Object.keys(grouped).forEach(label => {
                let emoji = '🧴';
                if (label.toLowerCase() === 'face mask') emoji = '🧖‍♀️';
                if (label.toLowerCase() === 'moisturizer') emoji = '💧';

                let rows = grouped[label].map(p => `
                    <tr>
                        <td class="product-name">${p.title}</td>
                        <td>${p.price}</td>
                    </tr>
                `).join('');

                recommendedProducts.innerHTML += `
                    <div class="bg-white rounded-xl shadow p-6">
                        <div class="label-title">${emoji} ${label}</div>
                        <table class="product-table">
                            <thead>
                                <tr>
                                    <th>Product Name</th>
                                    <th>Price</th>
                                </tr>
                            </thead>
                            <tbody>${rows}</tbody>
                        </table>
                    </div>
                `;
            });
        }
    </script>
</x-app-layout>
