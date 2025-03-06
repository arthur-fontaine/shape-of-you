<script lang="ts">
  import type { IClothing, IClothingLink } from "../types/Clothing.d";
  import * as Sheet from "../components/sheet";

  let {
    clothing,
    titleStyle = "small",
  }: {
    clothing: IClothing;
    titleStyle?: "small" | "big";
  } = $props();

  function formatPrice(priceCts: number): string {
    return (priceCts / 100).toLocaleString("fr-FR", {
      style: "currency",
      currency: "EUR",
    });
  }

  const lowestPrice = Math.min(
    ...clothing.links.map((link) =>
      Math.min(...link.prices.map((p) => p.priceCts)),
    ),
  );
</script>

<div class="mx-4 my-8">
  <a href="/clothing/{clothing.id}" class="contents">
    <h3 class="title-3 {titleStyle === 'small' && 'font-semibold'}">
      Nike Sportswear
      {#if titleStyle === "small"}
        <span class="font-normal">{formatPrice(lowestPrice)}</span>
      {/if}
    </h3>
    <h1 class={titleStyle === "big" ? "title-1" : "title-3"}>
      {clothing.name}
    </h1>

    {#if lowestPrice !== Infinity && titleStyle === "big"}
      <p class="text-lg font-normal">{formatPrice(lowestPrice)}</p>
    {/if}

    <img
      src={clothing.imageUrl}
      alt={clothing.name}
      class="w-full max-h-[75%] object-cover object-center rounded-2xl my-4"
    />
  </a>

  <div class="flex flex-col gap-4">
    <a
      class="button flex items-center justify-center gap-2"
      href={clothing.links[0].url}
    >
      <span class="icon-[tabler--shopping-bag] text-2xl"></span>
      Acheter
    </a>
  </div>
</div>
