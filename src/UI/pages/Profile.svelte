<script lang="ts">
    import type { IUser} from "../types/User";
    import InfoUser from "../components/InfoUser.svelte";
    import Post from "../components/Post.svelte";

    const {user}: {
        user: IUser;
    } = $props();
</script>

<InfoUser {user} />
<div class="max-w-5xl mx-auto mt-6 grid grid-cols-1 gap-4 px-4">
    {#each user.posts.sort((a, b) => new Date(b.createdAt).getTime() - new Date(a.createdAt).getTime()) as post (post.id)}
        <Post {post} hideRateSlider />
    {/each}
</div>