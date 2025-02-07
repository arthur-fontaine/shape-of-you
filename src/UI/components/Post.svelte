<script lang="ts">
  import type { IPost } from "../types/Post";
  import CustomSlider from "./CustomSlider.svelte";
  import { createMutation } from "../utils/query";
  import { debounce } from "lodash-es";

  const { post }: { post: IPost } = $props();

  let hasRated = $state<boolean>(
    post.myRate !== undefined && post.myRate !== null,
  );
  let rate10 = $state<number>(post.myRate ?? 5);

  const rateMutation = createMutation(`/posts/${post.postId}/rate`, 'PATCH');
  const rateMutate = debounce($rateMutation.mutate, 500);

  $effect(() => {
    if (hasRated) {
      rateMutate({ rate: rate10 });
    }
  });
</script>

<div class="mb-3 bg-white shadow-md rounded-lg overflow-hidden">
  <div class="p-4">
    <h3 class="text-lg font-semibold">{post.authorName}</h3>
  </div>
  <img src={post.mediaUrls[0]} alt="Post" class="w-full h-auto" />
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
          {#if hasRated}
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
          {/if}
        </span>
        🔥
      </span>
    </CustomSlider>
  </div>
</div>
