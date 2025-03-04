<script lang="ts">
  import type { IPost } from "../types/Post";
  import CustomSlider from "./CustomSlider.svelte";
  import { createMutation } from "../utils/query";
  import { debounce } from "lodash-es";

  const { post, hideRateSlider }: { post: IPost; hideRateSlider?: boolean } =
    $props();

  let hasRated = $state<boolean>(
    post.myRate !== undefined && post.myRate !== null,
  );
  let rate10 = $state<number>(post.myRate ?? 5);

  const rateMutation = createMutation<
    void,
    {
      rate: number;
    }
  >(`/posts/${post.postId}/rate`, "PATCH");
  const rateMutate = debounce($rateMutation.mutate, 500);

  $effect(() => {
    if (hasRated) {
      rateMutate({ rate: rate10 });
    }
  });
</script>

<div class="mb-3">
  <header class="px-2 mb-4">
    <h3 class="text-sm font-semibold">{post.authorName}</h3>
    <p class="text-sm text-gray-500">{post.text}</p>
  </header>
  <img src={post.mediaUrls[0]} alt="Post" class="w-full h-auto rounded-card" />
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
