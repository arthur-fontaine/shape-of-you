<script lang="ts">
  import ClothingPiece from "../components/ClothingPiece.svelte";
  import type { IClothing } from "../types/Clothing";

  const props: {
    recommendations: {
      clothing: IClothing;
      type: "vector";
    }[];
  } = $props();

  const { recommendations } = props;
</script>

<h1 class="text-2xl font-bold mt-18 px-4">Pour vous</h1>

<p class="text-sm text-disabled px-4 mt-3">
  <span class="icon-[tabler--help] align-text-top mt-0.25"></span>
  La section <span class="italic">Pour vous</span> contient des recommandations basées sur vos interactions.
</p>

{#each recommendations as { clothing }}
  <ClothingPiece {clothing} titleStyle="small" />
{/each}

<div class="h-6"></div>
