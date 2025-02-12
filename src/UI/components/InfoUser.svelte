<script lang="ts">
    import type { IUser } from "../types/User";
    import profile from "../assets/icons/profile.svg";

    const { user }: {
        user: IUser;
    } = $props();
</script>

<div class="bg-white shadow-md">
    <div class="max-w-5xl mx-auto p-4">
        <div class="flex items-center space-x-4">
            <img src={profile} alt="profile" class="w-24 h-24 rounded-full border">
            <div>
                <h2 class="text-2xl font-bold">{user.name}</h2>
            </div>
        </div>
        <div class="mt-4 flex space-x-8">
            <div>
                <span class="font-bold">{(user.posts).length}</span> posts
            </div>
            <div>
                <span class="font-bold">{(user.friends).length}</span> followers
            </div>
        </div>
        <div class="mt-4 flex space-x-4">
            <a href="/profil" class="bg-blue-500 text-white px-4 py-2 rounded">Publication</a>
            <a href="/bookmark" class="bg-gray-500 text-white px-4 py-2 rounded">Bookmark</a>
        </div>
    </div>
</div>