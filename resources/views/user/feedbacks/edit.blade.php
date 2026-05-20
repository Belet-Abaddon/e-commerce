<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-blue-800 leading-tight">
                {{ __('Edit Feedback') }}
            </h2>
            <a href="{{ route('user.feedbacks.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-700">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Feedbacks
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b">
                    <h3 class="font-semibold text-gray-800">Edit Your Feedback</h3>
                </div>

                <form action="{{ route('user.feedbacks.update', $feedback->id) }}" method="POST" class="p-6">
                    @csrf
                    @method('PUT')

                    <!-- Rating -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Your Rating *
                        </label>
                        <div class="flex items-center space-x-2">
                            <div class="flex items-center space-x-1" id="ratingStars">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star text-2xl cursor-pointer transition-colors {{ $i <= $feedback->rating ? 'text-yellow-400' : 'text-gray-300' }}" data-rating="{{ $i }}"></i>
                                @endfor
                            </div>
                            <input type="hidden" name="rating" id="rating" value="{{ $feedback->rating }}" required>
                            <span id="ratingText" class="text-sm text-gray-500 ml-2"></span>
                        </div>
                        @error('rating')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Feedback Message -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Your Feedback *
                        </label>
                        <textarea name="feedback" rows="5" required 
                                  class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none">{{ old('feedback', $feedback->feedback) }}</textarea>
                        @error('feedback')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end space-x-3 pt-4 border-t">
                        <a href="{{ route('user.feedbacks.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                            Cancel
                        </a>
                        <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                            <i class="fas fa-save mr-2"></i>
                            Update Feedback
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    const stars = document.querySelectorAll('#ratingStars i');
    const ratingInput = document.getElementById('rating');
    const ratingText = document.getElementById('ratingText');
    
    const ratingMessages = { 1: 'Very Poor', 2: 'Poor', 3: 'Average', 4: 'Good', 5: 'Excellent' };
    
    const initialRating = parseInt(ratingInput.value);
    ratingText.textContent = ratingMessages[initialRating];
    
    stars.forEach(star => {
        star.addEventListener('click', function() {
            const rating = parseInt(this.dataset.rating);
            ratingInput.value = rating;
            ratingText.textContent = ratingMessages[rating];
            
            stars.forEach((s, index) => {
                if (index < rating) {
                    s.classList.remove('text-gray-300');
                    s.classList.add('text-yellow-400');
                } else {
                    s.classList.remove('text-yellow-400');
                    s.classList.add('text-gray-300');
                }
            });
        });
        
        star.addEventListener('mouseenter', function() {
            const rating = parseInt(this.dataset.rating);
            stars.forEach((s, index) => {
                if (index < rating) {
                    s.classList.remove('text-gray-300');
                    s.classList.add('text-yellow-200');
                }
            });
        });
        
        star.addEventListener('mouseleave', function() {
            const currentRating = parseInt(ratingInput.value) || 0;
            stars.forEach((s, index) => {
                if (index < currentRating) {
                    s.classList.remove('text-yellow-200');
                    s.classList.add('text-yellow-400');
                } else {
                    s.classList.remove('text-yellow-200');
                    s.classList.add('text-gray-300');
                }
            });
        });
    });
</script>
@endsection