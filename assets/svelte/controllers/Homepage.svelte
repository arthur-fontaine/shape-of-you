<script lang="ts">
    let posts = [
        { id: 1, user: 'user1', image: './image/image1.webp', likes: 0 },
        { id: 2, user: 'user2', image: './image/image1.webp', likes: 0 },
        // Add more posts as needed
    ];

    function likePost(postId: number) {
        const post = posts.find(p => p.id === postId);
        if (post) {
            post.likes += 1;
        }
    }
</script>

<div class="space-y-4 p-4">
    {#each posts as post}
        <div class="mb-3 bg-white shadow-md rounded-lg overflow-hidden">
            <div class="p-4">
                <h3 class="text-lg font-semibold">{post.user}</h3>
            </div>
            <img src={post.image} alt="Post image" class="w-full h-auto">
            <div class="p-4 flex items-center justify-between">
                <button class="bg-none border-none text-red-500 cursor-pointer" on:click={() => likePost(post.id)}>❤️ {post.likes}</button>
            </div>
        </div>
    {/each}
</div>
