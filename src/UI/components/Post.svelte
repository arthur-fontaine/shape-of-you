<script lang="ts">
  import type { IPost } from "../types/Post";
  import CustomSlider from "./CustomSlider.svelte";
  import { createMutation } from "../utils/query";
  import { debounce } from "lodash-es";

  const { post, hideRateSlider, currentUserId }: { 
    post: IPost; 
    hideRateSlider?: boolean;
    currentUserId?: number; 
  } = $props();

  let hasRated = $state<boolean>(
    post.myRate !== undefined && post.myRate !== null,
  );
  let rate10 = $state<number>(post.myRate ?? 5);

  const rateMutation = createMutation<
    void,
    {
      rate: number;
    }
  >(`/posts/${post.id}/rate`, "PATCH");
  const rateMutate = debounce($rateMutation.mutate, 500);

  // Create delete mutation
  const deleteMutation = createMutation<void, void>(`/posts/${post.id}/delete`, "POST");
  
  // Handle post deletion
  function deletePost() {
    if (confirm("Are you sure you want to delete this post?")) {
      $deleteMutation.mutate();
      // You might want to add logic to remove this post from the UI immediately
      // or navigate away depending on your application structure
    }
  }

  $effect(() => {
    if (hasRated) {
      rateMutate({ rate: rate10 });
    }
  });
</script>

<div class="mb-3 relative">
  <header class="px-2 mb-4 flex justify-between items-start">
    <div>
      <h3 class="text-sm font-semibold">{post.authorName}</h3>
      <p class="text-sm text-gray-500">{post.text}</p>
    </div>
    
    {#if post.isMyPost}
      <button 
        on:click={deletePost}
        class="text-red-500 hover:text-red-700 p-1 rounded-full hover:bg-gray-100 transition"
        aria-label="Delete post"
      >
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M3 6h18"></path>
          <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
          <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
        </svg>
      </button>
    {/if}
  </header>
  
  <img src={
    post.mediaUrls[0]?.startsWith("http")
      ? post.mediaUrls[0]
      : `/data/${post.mediaUrls[0]}`
  } alt="Post" class="w-full h-auto rounded-card" />
  
  {#if !hideRateSlider}
    <div class="p-4 flex items-center justify-between">
      <CustomSlider
        min={0}
        max={10}
        step={0.01}
        bind:value={rate10}
        onUserChange={() => (hasRated = true)}
      >
        <span class="relative">
          <span
            class="absolute top-0 left-1/2 transform -translate-x-1/2 -translate-y-full bg-white px-1 text-sm w-min whitespace-nowrap"
          >
            <!-- {#if hasRated}
            {#if rate10 < 5}
              Bof...
            {:else if rate10 < 7}
              Pas mal
            {:else if rate10 < 9}
              Super !
            {:else}
              Génial !!
            {/if}
          {:else}
            Notez ce post
          {/if} -->
          </span>
          🔥
        </span>
      </CustomSlider>
    </div>
  {/if}
</div>
