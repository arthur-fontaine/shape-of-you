<script lang="ts">
    import type { IClothing } from '../types/Clothing';
    import type { IUser } from '../types/User';

    let searchQuery: string = '';
    let clothingResults: IClothing[] = [];
    let userResults: IUser[] = [];
    let isLoading: boolean = false;
    let error: string | null = null;

    async function search(): Promise<void> {
        if (searchQuery.length < 1) {
            clothingResults = [];
            userResults = [];
            return;
        }

        isLoading = true;
        error = null;
        
        try {
            const [clothingResponse, userResponse] = await Promise.all([
                fetch(`/api/clothing/search?q=${encodeURIComponent(searchQuery)}`),
                fetch(`/api/users/search?q=${encodeURIComponent(searchQuery)}`)
            ]);

            if (!clothingResponse.ok || !userResponse.ok) {
                throw new Error(`Search failed`);
            }

            [clothingResults, userResults] = await Promise.all([
                clothingResponse.json(),
                userResponse.json()
            ]);
        } catch (err) {
            console.error('Search error:', err);
            error = err instanceof Error ? err.message : 'An unknown error occurred';
            clothingResults = [];
            userResults = [];
        } finally {
            isLoading = false;
        }
    }

    function handleKeyPress(event: KeyboardEvent): void {
        if (event.key === 'Enter') {
            search();
        }
    }

    function goToClothing(id: number): void {
        window.location.href = `/clothing/${id}`;
    }

    function goToProfile(id: number): void {
        window.location.href = `/profil/${id}`;
    }
</script>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="space-y-6">
        <!-- Search Input with Button -->
        <div class="flex gap-2">
            <input
                type="text"
                bind:value={searchQuery}
                on:keypress={handleKeyPress}
                placeholder="Search for clothes or users..."
                class="flex-1 px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
            />
            <button
                on:click={search}
                class="px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition duration-200 disabled:opacity-50"
                disabled={searchQuery.length < 1}
            >
                Search
            </button>
        </div>

        {#if isLoading}
            <div class="flex justify-center items-center py-8">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500"></div>
            </div>
        {:else}
            {#if userResults.length > 0}
                <div class="space-y-4">
                    <h2 class="text-xl font-semibold text-gray-900">Users</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                        {#each userResults as user (user.userId)}
                            <div 
                                class="bg-white rounded-lg shadow-md p-4 cursor-pointer hover:shadow-lg transition-shadow duration-200"
                                on:click={() => goToProfile(user.userId)}
                                on:keydown={(e) => e.key === 'Enter' && goToProfile(user.userId)}
                                role="button"
                                tabindex="0"
                            >
                                <h3 class="text-lg font-semibold text-gray-900">{user.name}</h3>
                                <p class="text-sm text-gray-500">{user.email}</p>
                            </div>
                        {/each}
                    </div>
                </div>
            {/if}

            <!-- Clothing Section -->
            {#if clothingResults.length > 0}
                <div class="space-y-4">
                    <h2 class="text-xl font-semibold text-gray-900">Clothes</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                        {#each clothingResults as item (item.id)}
                            <div 
                                class="bg-white rounded-lg shadow-md overflow-hidden cursor-pointer hover:shadow-lg transition-shadow duration-200"
                                on:click={() => goToClothing(item.id)}
                                on:keydown={(e) => e.key === 'Enter' && goToClothing(item.id)}
                                role="button"
                                tabindex="0"
                            >
                                {#if item.imageUrl}
                                    <img
                                        src={item.imageUrl}
                                        alt={item.name}
                                        class="w-full h-48 object-cover"
                                    />
                                {/if}
                                <div class="p-4">
                                    <h3 class="text-lg font-semibold text-gray-900">{item.name}</h3>
                                    <p class="text-sm text-gray-600">{item.type}</p>
                                </div>
                            </div>
                        {/each}
                    </div>
                </div>
            {/if}

            {#if userResults.length === 0 && clothingResults.length === 0 && searchQuery.length >= 2}
                <div class="text-center py-8 text-gray-500">
                    No results found
                </div>
            {/if}
        {/if}
    </div>
</div>