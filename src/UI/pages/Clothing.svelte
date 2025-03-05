<script lang="ts">
  import type { FormEventHandler } from "svelte/elements";
  import type { IClothing, IClothingLink } from "../types/Clothing.d";
  import { createMutation } from "../utils/query";
  import { debounce } from "lodash-es";
  import * as Sheet from "../components/sheet";

  let {
    clothing,
    links,
  }: {
    clothing: IClothing;
    links: IClothingLink[];
  } = $props();

  function formatPrice(priceCts: number): string {
    return (priceCts / 100).toLocaleString("fr-FR", {
      style: "currency",
      currency: "EUR",
    });
  }

  const lowestPrice = Math.min(...links.map((link) => link.currentPrice?.priceCts ?? Infinity));

  const addToListMutation = createMutation<unknown, void>(
    `/clothing/${clothing.id}/add-to-list`,
  );

  const addToDressingMutation = createMutation<unknown, void>(
    `/clothing/${clothing.id}/add-to-dressing`,
    undefined,
    {
      onMutate: () => (clothing = { ...clothing, dressing: {} }),
      onError: () => (clothing = { ...clothing, dressing: undefined }),
    },
  );

  const removeFromDressingMutation = createMutation<unknown, void>(
    `/clothing/${clothing.id}/remove-from-dressing`,
    undefined,
    {
      onMutate: () => (clothing = { ...clothing, dressing: undefined }),
      onError: () => (clothing = { ...clothing, dressing: {} }),
    },
  );

  const updateToDressingMutation = createMutation<
    unknown,
    {
      comment?: string;
      rate?: string;
    }
  >(`/clothing/${clothing.id}/upsert-to-dressing`, undefined, {
    onMutate: ({ comment, rate }) => {
      clothing = {
        ...clothing,
        dressing: {
          ...clothing.dressing,
          ...(comment && { comment }),
          ...(rate && { rate: parseInt(rate) }),
        },
      };
    },
    onError: () => {
      clothing = {
        ...clothing,
        dressing: {
          comment: undefined,
          rate: undefined,
        },
      };
    },
  });

  const debouncedUpdateToDressingRate = debounce((rate: string) => {
    $updateToDressingMutation.mutate({ rate });
  }, 500);

  const updateDressingRate: FormEventHandler<HTMLInputElement> = (e) => {
    const rate = e.currentTarget.value;
    debouncedUpdateToDressingRate(rate);
  };

  const debouncedUpdateDressingComment = debounce((comment: string) => {
    $updateToDressingMutation.mutate({ comment });
  }, 500);

  const updateDressingComment: FormEventHandler<HTMLTextAreaElement> = (e) => {
    const comment = e.currentTarget.value;
    debouncedUpdateDressingComment(comment);
  };
</script>

<div class="absolute right-4 top-6">
  <Sheet.Root>
    <Sheet.Trigger>
      <span class="{clothing.bookmarked ? 'icon-[tabler--bookmark-filled]' : 'icon-[tabler--bookmark]'} text-2xl"></span>
    </Sheet.Trigger>
    <Sheet.Content side="bottom">
      <iframe
        src="/clothing/{clothing.id}/add-to-bookmarks"
        title="Add to bookmarks"
        class="w-full h-96"
      >
      </iframe>
    </Sheet.Content>
  </Sheet.Root>
</div>

<div class="mx-4 mb-6">
  <h3 class="title-3">Nike Sportswear</h3>
  <h1 class="title-1">{clothing.name}</h1>

  {#if lowestPrice !== Infinity}
    <p class="text-lg font-normal">{formatPrice(lowestPrice)}</p>
  {/if}

  <img
    src={clothing.imageUrl}
    alt={clothing.name}
    class="w-full max-h-[75%] object-cover object-center rounded-2xl my-6"
  />

  <div class="flex flex-col gap-4">
    <a
      class="button flex items-center justify-center gap-2"
      href={links[0].url}
    >
      <span class="icon-[tabler--shopping-bag] text-2xl"></span>
      Acheter
    </a>
    <button
      class="button secondary flex items-center justify-center gap-2"
      onclick={() =>
        clothing.dressing
          ? $removeFromDressingMutation.mutate()
          : $addToDressingMutation.mutate()}
    >
      {#if clothing.dressing}
        <span class="icon-[tabler--check] text-2xl"></span>
        Possédé
      {:else}
        <span class="icon-[tabler--plus] text-2xl"></span>
        Ajouter à ma garde-robe
      {/if}
    </button>

    {#if clothing.dressing}
      <div class="flex flex-col gap-2">
        <label for="rate" class="text-sm">Note</label>
        <input
          type="number"
          name="rate"
          id="rate"
          min="0"
          max="10"
          step="1"
          class="input"
          value={clothing.dressing.rate}
          oninput={updateDressingRate}
        />
      </div>
      <div class="flex flex-col gap-2 mb-6">
        <label for="comment" class="text-sm">Commentaire</label>
        <textarea
          name="comment"
          id="comment"
          class="input"
          rows="3"
          value={clothing.dressing.comment}
          oninput={updateDressingComment}
        ></textarea>
      </div>
    {/if}
  </div>
</div>
