<script lang="ts">
  import type { IPost } from "../types/Post";
  import CustomSlider from "./CustomSlider.svelte";

  const { post }: { post: IPost } = $props();

  let rate10 = $state<number>(post.myRate);
</script>

<div class="mb-3 bg-white shadow-md rounded-lg overflow-hidden">
  <div class="p-4">
    <h3 class="text-lg font-semibold">{post.authorName}</h3>
  </div>
  <img src={post.mediaUrls[0]} alt="Post" class="w-full h-auto" />
  <div class="p-4 flex items-center justify-between">
    <CustomSlider min={0} max={10} step={0.01} bind:value={rate10}>
      <span class="relative">
        <span class="absolute top-0 left-0 text-xs text-gray-500">
          {rate10.toFixed(2)}
        </span>
        🔥
      </span>
    </CustomSlider>
  </div>
</div>
