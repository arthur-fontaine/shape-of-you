<script lang="ts">
    import type { IUser} from "../types/User";
    import profile from "../assets/icons/profile.svg";
    import InfoUser from "../components/InfoUser.svelte";

    const {user}: {
        user: IUser;
    } = $props();
</script>

<div class="min-h-screen bg-gray-100">
    <InfoUser {user} />
    <div class="max-w-5xl mx-auto mt-6 grid grid-cols-3 gap-4">
        {#each user.posts as post (post.id)}
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <img src="{post.mediaUrls[0] || './image/default_post.png'}" alt="Post" class="w-full h-48 object-cover">
                <div class="p-4">
                    <p class="text-gray-800">{post.text}</p>
                </div>
            </div>
        {/each}
    </div>
</div>