<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-blue-800 leading-tight">
                {{ __('My Feedbacks') }}
            </h2>
            <button onclick="openModal()" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                <i class="fas fa-plus mr-2"></i>
                Write Feedback
            </button>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            @if($feedbacks->count() > 0)
                <div class="space-y-4">
                    @foreach($feedbacks as $feedback)
                    <div class="bg-white rounded-lg shadow-sm p-5 border border-gray-100">
                        <div class="flex justify-between items-start mb-3">
                            <div class="flex items-center space-x-1">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star {{ $i <= $feedback->rating ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                                @endfor
                            </div>
                            <div class="flex items-center space-x-3">
                                <span class="text-sm text-gray-500">{{ $feedback->created_at->format('M d, Y') }}</span>
                                <button onclick="deleteFeedback({{ $feedback->id }})" class="text-red-500 hover:text-red-700">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                        <p class="text-gray-700">{{ $feedback->feedback }}</p>
                    </div>
                    @endforeach
                </div>
                <div class="mt-6">
                    {{ $feedbacks->links() }}
                </div>
            @else
                <div class="bg-white rounded-lg shadow-sm p-12 text-center">
                    <i class="fas fa-star text-5xl text-gray-300 mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No Feedbacks Yet</h3>
                    <p class="text-gray-500 mb-4">You haven't written any feedback yet.</p>
                    <button onclick="openModal()" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">
                        <i class="fas fa-plus mr-2"></i>
                        Write Your First Feedback
                    </button>
                </div>
            @endif
        </div>
    </div>

    <!-- Modal -->
    <div id="feedbackModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-lg bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Write Your Feedback</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <form action="{{ route('user.feedbacks.store') }}" method="POST">
                @csrf

                <!-- Rating -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Your Rating *
                    </label>
                    <div class="flex items-center space-x-1" id="ratingStars">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="fas fa-star text-2xl cursor-pointer text-gray-300 hover:text-yellow-400 transition-colors" data-rating="{{ $i }}"></i>
                        @endfor
                    </div>
                    <input type="hidden" name="rating" id="rating" required>
                    <p id="ratingError" class="mt-1 text-sm text-red-600 hidden">Please select a rating</p>
                </div>

                <!-- Feedback -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Your Feedback *
                    </label>
                    <textarea name="feedback" id="feedbackText" rows="4" required
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                              placeholder="Share your experience with us..."></textarea>
                    <p id="feedbackError" class="mt-1 text-sm text-red-600 hidden">Please enter your feedback (minimum 10 characters)</p>
                </div>

                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="closeModal()" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">
                        <i class="fas fa-paper-plane mr-2"></i>
                        Submit Feedback
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Modal functions
        function openModal() {
            document.getElementById('feedbackModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('feedbackModal').classList.add('hidden');
            // Reset form
            document.getElementById('rating').value = '';
            document.getElementById('feedbackText').value = '';
            // Reset stars
            const stars = document.querySelectorAll('#ratingStars i');
            stars.forEach(star => {
                star.classList.remove('text-yellow-400');
                star.classList.add('text-gray-300');
            });
            // Hide errors
            document.getElementById('ratingError').classList.add('hidden');
            document.getElementById('feedbackError').classList.add('hidden');
        }

        // Rating stars
        const stars = document.querySelectorAll('#ratingStars i');
        const ratingInput = document.getElementById('rating');
        
        stars.forEach(star => {
            star.addEventListener('click', function() {
                const rating = parseInt(this.dataset.rating);
                ratingInput.value = rating;
                
                stars.forEach((s, index) => {
                    if (index < rating) {
                        s.classList.remove('text-gray-300');
                        s.classList.add('text-yellow-400');
                    } else {
                        s.classList.remove('text-yellow-400');
                        s.classList.add('text-gray-300');
                    }
                });
                
                document.getElementById('ratingError').classList.add('hidden');
            });
        });

        // Delete feedback
        function deleteFeedback(id) {
            if (confirm('Are you sure you want to delete this feedback?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/feedbacks/${id}`;
                form.innerHTML = '@csrf @method("DELETE")';
                document.body.appendChild(form);
                form.submit();
            }
        }

        // Form validation before submit
        document.querySelector('#feedbackModal form').addEventListener('submit', function(e) {
            let isValid = true;
            
            const rating = document.getElementById('rating').value;
            if (!rating) {
                document.getElementById('ratingError').classList.remove('hidden');
                isValid = false;
            }
            
            const feedback = document.getElementById('feedbackText').value;
            if (!feedback || feedback.length < 10) {
                document.getElementById('feedbackError').classList.remove('hidden');
                isValid = false;
            }
            
            if (!isValid) {
                e.preventDefault();
            }
        });
    </script>
</x-app-layout>