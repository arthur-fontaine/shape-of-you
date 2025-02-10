<script lang="ts">
    import type { IUser} from "../types/User";
    import type { IClothingList } from "../types/ClothingList";

    const {user}: {
        user: IUser;
    } = $props();
    const parsedUser: IUser = JSON.parse(user);
</script>

<div class="min-h-screen bg-gray-100">
    <div class="bg-white shadow-md">
        <div class="max-w-5xl mx-auto p-4">
            <div class="flex items-center space-x-4">
                <img src="./image/icon_user.png" alt="profile" class="w-24 h-24 rounded-full border">
                <div>
                    <h2 class="text-2xl font-bold">{parsedUser.name}</h2>
                </div>
            </div>
            <div class="mt-4 flex space-x-8">
                <div>
                    <span class="font-bold">{(parsedUser.posts).length}</span> posts
                </div>
                <div>
                    <span class="font-bold">{(parsedUser.friends).length}</span> followers
                </div>
            </div>
            <div class="mt-4 flex space-x-4">
                <a href="/profil" class="bg-blue-500 text-white px-4 py-2 rounded">Publication</a>
                <a href="/bookmark" class="bg-gray-500 text-white px-4 py-2 rounded">Bookmark</a>
            </div>
        </div>
    </div>
    <div class="max-w-5xl mx-auto mt-6 grid grid-cols-3 gap-4">
        {#each parsedUser.clothingLists as clothingList (clothingList.id)}
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <img src="" alt="Bookmark" class="w-full h-48 object-cover">
                <div class="p-4">
                    <p class="text-gray-800">{clothingList.name}</p>
                </div>
            </div>
        {/each}
    </div>
</div>