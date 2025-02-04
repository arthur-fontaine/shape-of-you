<script lang="ts">
    let posts = [
        { id: 1, user: 'user1', image: './image/image1.webp', likes: 0 },
        { id: 2, user: 'user2', image: './image/image1.webp', likes: 0 },
        // Add more posts as needed
    ];

    function likePost(postId) {
        const post = posts.find(p => p.id === postId);
        if (post) {
            post.likes += 1;
        }
    }
</script>

<style>
    .navbar {
        position: fixed;
        bottom: 0;
        width: 100%;
        background-color: #fff;
        border-top: 1px solid #ddd;
        display: flex;
        justify-content: space-around;
        padding: 10px 0;
    }

    .post {
        margin-bottom: 20px;
    }

    .post img {
        width: 100%;
        height: auto;
    }

    .like-btn {
        background: none;
        border: none;
        color: #ff0000;
        cursor: pointer;
    }
</style>

<div class="posts">
    {#each posts as post}
        <div class="post">
            <h3>{post.user}</h3>
            <img src={post.image} alt="Post image">
            <button class="like-btn" on:click={() => likePost(post.id)}>❤️ {post.likes}</button>
        </div>
    {/each}
</div>

<div class="navbar">
    <a href="/">Home</a>
    <a href="/search">Search</a>
    <a href="/reels">Reels</a>
    <a href="/activity">Activity</a>
    <a href="/profile">Profile</a>
</div>