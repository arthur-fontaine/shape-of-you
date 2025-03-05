<script lang="ts">
    import Post from "../components/Post.svelte";
    import type { IPost } from "../types/Post";

    const props: {
        posts: IPost[];
    } = $props();

    const posts = props.posts;
</script>

<div class="space-y-4 p-4 pt-0">
    {#each posts as post}
        <Post {post} />
    {/each}
</div>
