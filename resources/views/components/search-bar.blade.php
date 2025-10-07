{{-- Enhanced Search Bar Component --}}
<div class="search-bar">
    <form action="{{ route('search') }}" method="GET" id="searchForm">
        <input type="text" 
               name="q" 
               id="searchInput"
               placeholder="Search for products, brands and more..." 
               value="{{ request('q') }}" 
               autocomplete="off" />
        <button type="submit" class="search-btn"><i class="ri-search-line"></i></button>
        
        <!-- Search Suggestions Dropdown -->
        <div id="searchSuggestions" class="search-suggestions hidden" style="display: none;">
            <div class="suggestions-content">
                <div id="suggestionsList" class="suggestions-list"></div>
                <div id="trendingSuggestions" class="trending-suggestions">
                    <div class="trending-header">
                        <i class="ri-fire-line"></i>
                        <span>Trending Searches</span>
                    </div>
                    <div id="trendingList" class="trending-list"></div>
                </div>
                <div id="popularRecommendations" class="popular-recommendations">
                    <div class="popular-header">
                        <i class="ri-star-line"></i>
                        <span>Popular Recommendations</span>
                    </div>
                    <div id="popularList" class="popular-list"></div>
                </div>
            </div>
        </div>
    </form>
</div>

{{-- Enhanced Search Bar Styles --}}
<style>
    /* Search Bar Layout Styles - Override external CSS */
    .search-bar {
        position: relative !important;
        display: flex !important;
        align-items: center !important;
        flex: 1 !important;
        max-width: 600px !important;
        margin: 0 auto !important;
        background: #fff !important;
        border: 1px solid #e5e7eb !important;
        border-radius: 8px !important;
        overflow: visible !important;
    }
    
    .search-bar form {
        position: relative !important;
        display: flex !important;
        width: 100% !important;
        height: 100% !important;
        background: transparent !important;
        border: none !important;
        border-radius: 0 !important;
        overflow: visible !important;
        z-index: 1 !important;
    }
    
    .search-bar input {
        flex: 1 !important;
        padding: 12px 16px !important;
        padding-right: 50px !important; /* Space for the search button */
        border: none !important;
        outline: none !important;
        font-size: 14px !important;
        background: transparent !important;
        width: auto !important;
    }
    
    .search-bar .search-btn {
        position: absolute !important;
        right: 0 !important;
        top: 0 !important;
        bottom: 0 !important;
        background: #2c3c8c !important;
        color: white !important;
        border: none !important;
        padding: 0 16px !important;
        cursor: pointer !important;
        transition: background 0.3s ease !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        border-radius: 0 8px 8px 0 !important;
        outline: none !important;
    }
    
    .search-bar .search-btn:hover {
        background: #1e40af !important;
    }
    
    .search-bar .search-btn i {
        font-size: 18px !important;
    }
    
    /* Search Suggestions Styles */
    .search-suggestions {
        position: absolute !important;
        top: 100% !important;
        left: 0 !important;
        right: 0 !important;
        background: white !important;
        border: 1px solid #e5e7eb !important;
        border-radius: 12px !important;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15) !important;
        z-index: 9999 !important;
        max-height: 450px !important;
        overflow-y: auto !important;
        margin-top: 4px !important;
        animation: slideDown 0.3s ease-out !important;
    }
    
    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .suggestions-content {
        padding: 20px;
    }
    
    .suggestions-list {
        margin-bottom: 20px;
    }
    
    .suggestion-item {
        display: flex;
        align-items: center;
        padding: 12px 16px;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s ease;
        margin-bottom: 6px;
        border: 1px solid transparent;
    }
    
    .suggestion-item:hover {
        background-color: #f8fafc;
        border-color: #e2e8f0;
        transform: translateX(4px);
    }
    
    .suggestion-item i {
        margin-right: 12px;
        color: #64748b;
        font-size: 18px;
    }
    
    .suggestion-text {
        flex: 1;
        color: #1e293b;
        font-size: 15px;
        font-weight: 500;
    }
    
    .trending-suggestions {
        border-top: 2px solid #f1f5f9;
        padding-top: 20px;
    }
    
    .trending-header {
        display: flex;
        align-items: center;
        margin-bottom: 12px;
        color: #64748b;
        font-size: 13px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.8px;
    }
    
    .trending-header i {
        margin-right: 8px;
        color: #f59e0b;
        font-size: 16px;
    }
    
    .trending-list {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }
    
    .trending-item {
        background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
        color: #475569;
        padding: 8px 14px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
        border: 1px solid #e2e8f0;
    }
    
    .trending-item:hover {
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }
    
    .search-suggestions.hidden {
        display: none !important;
    }
    
    /* Popular Recommendations Section */
    .popular-recommendations {
        border-top: 2px solid #f1f5f9;
        padding-top: 20px;
        margin-top: 20px;
    }
    
    .popular-header {
        display: flex;
        align-items: center;
        margin-bottom: 12px;
        color: #64748b;
        font-size: 13px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.8px;
    }
    
    .popular-header i {
        margin-right: 8px;
        color: #10b981;
        font-size: 16px;
    }
    
    .popular-list {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 8px;
    }
    
    .popular-item {
        background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
        color: #065f46;
        padding: 10px 16px;
        border-radius: 12px;
        font-size: 13px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
        border: 1px solid #a7f3d0;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .popular-item:hover {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }
    
    .popular-item i {
        font-size: 14px;
    }
</style>

{{-- Enhanced Search Bar JavaScript --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Search Suggestions Functionality
    const searchInput = document.getElementById('searchInput');
    const searchSuggestions = document.getElementById('searchSuggestions');
    const suggestionsList = document.getElementById('suggestionsList');
    const trendingList = document.getElementById('trendingList');
    const popularList = document.getElementById('popularList');
    let searchTimeout;

    console.log('Search component loaded:', {
        searchInput: !!searchInput,
        searchSuggestions: !!searchSuggestions,
        suggestionsList: !!suggestionsList,
        trendingList: !!trendingList,
        popularList: !!popularList
    });

    if (searchInput && searchSuggestions) {
        searchInput.addEventListener('input', function() {
            const query = this.value.trim();
            console.log('Input event triggered, query:', query);
            
            clearTimeout(searchTimeout);
            
            if (query.length < 2) {
                console.log('Query too short, hiding suggestions');
                hideSuggestions();
                return;
            }

            console.log('Setting timeout for suggestions fetch');
            searchTimeout = setTimeout(() => {
                console.log('Timeout triggered, fetching suggestions');
                fetchSuggestions(query);
            }, 300);
        });

        searchInput.addEventListener('focus', function() {
            const query = this.value.trim();
            console.log('Focus event triggered, query:', query);
            
            if (query.length >= 2) {
                // If there's already a query, fetch suggestions
                console.log('Query exists, fetching suggestions');
                fetchSuggestions(query);
            } else {
                // Show trending searches and popular recommendations when user focuses on empty search bar
                console.log('No query, loading trending searches and popular recommendations');
                loadTrendingSearches();
                loadPopularRecommendations();
                showSuggestions();
            }
        });

        // Add click event to search input as well
        searchInput.addEventListener('click', function() {
            const query = this.value.trim();
            console.log('Click event triggered, query:', query);
            
            if (query.length < 2) {
                console.log('No query, loading trending searches and popular recommendations');
                loadTrendingSearches();
                loadPopularRecommendations();
                showSuggestions();
            }
        });

        searchInput.addEventListener('blur', function() {
            // Hide suggestions after a short delay to allow clicking on suggestions
            setTimeout(() => {
                hideSuggestions();
            }, 200);
        });
        
        // Handle form submission
        const searchForm = document.getElementById('searchForm');
        if (searchForm) {
            searchForm.addEventListener('submit', function(e) {
                const query = searchInput.value.trim();
                if (!query) {
                    e.preventDefault();
                    return false;
                }
            });
        }

        // Hide suggestions when clicking outside
        document.addEventListener('click', function(e) {
            if (!searchInput.contains(e.target) && !searchSuggestions.contains(e.target)) {
                hideSuggestions();
            }
        });

        function showSuggestions() {
            console.log('Showing suggestions');
            searchSuggestions.classList.remove('hidden');
            searchSuggestions.style.display = 'block';
            console.log('Suggestions element:', searchSuggestions);
        }

        function hideSuggestions() {
            console.log('Hiding suggestions');
            searchSuggestions.classList.add('hidden');
            searchSuggestions.style.display = 'none';
        }

        async function fetchSuggestions(query) {
            try {
                console.log('Fetching suggestions for:', query);
                const response = await fetch(`/search/suggestions?q=${encodeURIComponent(query)}`, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                const data = await response.json();
                console.log('Received suggestions:', data);
                
                if (data.suggestions && data.suggestions.length > 0) {
                    displaySuggestions(data.suggestions);
                    // Hide popular recommendations when showing search suggestions
                    if (document.getElementById('popularRecommendations')) {
                        document.getElementById('popularRecommendations').style.display = 'none';
                    }
                    showSuggestions();
                } else {
                    console.log('No suggestions received');
                    hideSuggestions();
                }
            } catch (error) {
                console.error('Error fetching suggestions:', error);
                hideSuggestions();
            }
        }

        async function loadTrendingSearches() {
            try {
                console.log('Loading trending searches...');
                const response = await fetch('/search/trending', {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                const data = await response.json();
                console.log('Received trending searches:', data);
                
                if (data.trending && data.trending.length > 0) {
                    displayTrendingSearches(data.trending);
                    // Show popular recommendations section when loading trending searches
                    if (document.getElementById('popularRecommendations')) {
                        document.getElementById('popularRecommendations').style.display = 'block';
                    }
                } else {
                    console.log('No trending searches received');
                }
            } catch (error) {
                console.error('Error loading trending searches:', error);
            }
        }

        async function loadPopularRecommendations() {
            try {
                console.log('Loading popular recommendations...');
                const response = await fetch('/search/popular', {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                const data = await response.json();
                console.log('Received popular recommendations:', data);
                
                if (data.popular && data.popular.length > 0) {
                    displayPopularRecommendations(data.popular);
                    // Show popular recommendations section
                    if (document.getElementById('popularRecommendations')) {
                        document.getElementById('popularRecommendations').style.display = 'block';
                    }
                } else {
                    console.log('No popular recommendations received');
                }
            } catch (error) {
                console.error('Error loading popular recommendations:', error);
            }
        }

        function displaySuggestions(suggestions) {
            if (!suggestionsList) return;
            suggestionsList.innerHTML = '';
            
            if (suggestions.length === 0) {
                suggestionsList.innerHTML = '<div class="suggestion-item"><span class="suggestion-text">No suggestions found</span></div>';
                return;
            }

            suggestions.forEach(suggestion => {
                const item = document.createElement('div');
                item.className = 'suggestion-item';
                item.innerHTML = `
                    <i class="ri-search-line"></i>
                    <span class="suggestion-text">${suggestion}</span>
                `;
                
                item.addEventListener('click', () => {
                    searchInput.value = suggestion;
                    hideSuggestions();
                    if (document.getElementById('searchForm')) {
                        document.getElementById('searchForm').submit();
                    }
                });
                
                suggestionsList.appendChild(item);
            });
        }

        function displayTrendingSearches(trending) {
            if (!trendingList) return;
            trendingList.innerHTML = '';
            
            trending.forEach(term => {
                const item = document.createElement('div');
                item.className = 'trending-item';
                item.textContent = term;
                
                item.addEventListener('click', () => {
                    searchInput.value = term;
                    hideSuggestions();
                    if (document.getElementById('searchForm')) {
                        document.getElementById('searchForm').submit();
                    }
                });
                
                trendingList.appendChild(item);
            });
        }

        function displayPopularRecommendations(popular) {
            if (!popularList) return;
            popularList.innerHTML = '';
            
            popular.forEach(item => {
                const element = document.createElement('div');
                element.className = 'popular-item';
                element.innerHTML = `
                    <i class="${item.icon}"></i>
                    <span>${item.text}</span>
                `;
                
                element.addEventListener('click', () => {
                    // Navigate to category page or search for the category
                    if (item.category) {
                        window.location.href = `/products/category/${item.category}`;
                    } else {
                        searchInput.value = item.text;
                        hideSuggestions();
                        if (document.getElementById('searchForm')) {
                            document.getElementById('searchForm').submit();
                        }
                    }
                });
                
                popularList.appendChild(element);
            });
        }
    }
});
</script>
