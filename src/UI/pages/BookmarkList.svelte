<script lang="ts">
    import type { IUser} from "../types/User";
    import InfoUser from "../components/InfoUser.svelte";

    const {user}: {
        user: IUser;
    } = $props();
</script>

<div class="min-h-screen bg-gray-100">
    <InfoUser {user} />
    <div class="max-w-5xl mx-auto mt-6 grid grid-cols-3 gap-4">
        {#each user.clothingLists as clothingList (clothingList.id)}
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <a href="/bookmark/{clothingList.id}">
                    <img src="" alt="Bookmark" class="w-full h-48 object-cover">
                    <div class="p-4">
                        <p class="text-gray-800">{clothingList.name}</p>
                    </div>
                </a>
            </div>
        {/each}
    </div>
</div>