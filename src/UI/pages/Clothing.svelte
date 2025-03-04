<script lang="ts">
  import type { IClothing, IClothingLink } from "../types/Clothing.d";
  import { createMutation } from "../utils/query";

  const props: {
    clothing: IClothing;
    links: IClothingLink[];
  } = $props();

  function formatPrice(priceCts: number): string {
    return (priceCts / 100).toLocaleString("fr-FR", {
      style: "currency",
      currency: "EUR",
    });
  }

  const upsertToDressingMutation = createMutation<unknown, {
    comment?: string;
    rate?: string;
  } | void>(
    `/clothing/${props.clothing.id}/upsert-to-dressing`,
  );

  function updateDressing(e: SubmitEvent) {
    e.preventDefault();
    const formData = new FormData(e.target as HTMLFormElement);
    const comment = formData.get("comment") as string;
    const rate = formData.get("rate") as string;
    $upsertToDressingMutation.mutate({ comment, rate });
  }
</script>

<div class="mx-4">
  
  <h3 class="title-3">Nike Sportswear</h3>
  <h1 class="title-1">{props.clothing.name}</h1>

  <img 
    src={props.clothing.imageUrl} 
    alt={props.clothing.name}
    class="w-full max-h-[75%] object-cover object-center rounded-2xl my-6"
  />

</div>

<!-- <div class="container mx-auto px-4 py-8">
  <div class="relative h-96 w-full mb-6">
    <img
      src={props.clothing.imageUrl}
      alt={props.clothing.name}
      class="w-full h-full object-cover rounded-lg shadow-lg"
    />
  </div>

  <div class="space-y-4">
    <h1 class="text-2xl font-bold">{props.clothing.name}</h1>
    
    <div class="flex gap-2 items-center">
      <span class="text-gray-600">{props.clothing.type}</span>
      <div class="flex gap-1">
        {#each props.clothing.color as color}
          <div
            class="w-4 h-4 rounded-full"
            style="background-color: {color}"
          ></div>
        {/each}
      </div>
    </div>

    <div>
      <a
        class="bg-blue-600 text-white px-4 py-2 rounded-lg"
        href="/clothing/{props.clothing.id}/add-to-bookmarks"
      >
        Ajouter à une collection
      </a>
      {#if !props.clothing.isInDressing}
        <button
          class="bg-blue-600 text-white px-4 py-2 rounded-lg"
          onclick={() => $upsertToDressingMutation.mutate()}
        >
          Ajouter au dressing
        </button>
      {/if}
    </div>

    {#if props.clothing.isInDressing}
      <form onsubmit={updateDressing}>
        <div class="flex gap-4">
          <input
            type="text"
            placeholder="Commentaire"
            name="comment"
            class="w-full p-2 border border-gray-200 rounded-lg"
            value={props.clothing.comment}
          />
          <input
            type="number"
            placeholder="Note"
            name="rate"
            class="w-24 p-2 border border-gray-200 rounded-lg"
            value={props.clothing.rate}
            min="0"
            max="10"
          />
          <button
            type="submit"
            class="bg-blue-600 text-white px-4 py-2 rounded-lg"
          >
            Enregistrer
          </button>
        </div>
      </form>
    {/if}

    <section class="mt-6">
      <h2 class="text-sm font-medium text-gray-500 mb-3">Mesures</h2>
      <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
        {#if props.clothing.measurements.chest}
          <div class="bg-white p-3 rounded-lg border border-gray-200">
            <span class="text-gray-500 text-sm">Tour de poitrine</span>
            <p class="font-medium">{props.clothing.measurements.chest} cm</p>
          </div>
        {/if}

        {#if props.clothing.measurements.waist}
          <div class="bg-white p-3 rounded-lg border border-gray-200">
            <span class="text-gray-500 text-sm">Tour de taille</span>
            <p class="font-medium">{props.clothing.measurements.waist} cm</p>
          </div>
        {/if}

        {#if props.clothing.measurements.length}
          <div class="bg-white p-3 rounded-lg border border-gray-200">
            <span class="text-gray-500 text-sm">Longueur</span>
            <p class="font-medium">{props.clothing.measurements.length} cm</p>
          </div>
        {/if}
      </div>

      {#if Object.keys(props.clothing.measurements).length === 0}
        <p class="text-gray-500 text-sm italic">Aucune mesure disponible</p>
      {/if}
    </section>

    <div class="flex gap-4">
      {#if props.clothing.ecologyRate5}
        <div class="flex items-center gap-1">
          <span class="text-green-600">Eco</span>
          <div class="flex">
            {#each Array(5) as _, i}
              <div
                class="w-4 h-4 {i < props.clothing.ecologyRate5
                  ? 'text-green-500'
                  : 'text-gray-300'}"
              >
                ★
              </div>
            {/each}
          </div>
        </div>
      {/if}

      {#if props.clothing.socialRate5}
        <div class="flex items-center gap-1">
          <span class="text-blue-600">Social</span>
          <div class="flex">
            {#each Array(5) as _, i}
              <div
                class="w-4 h-4 {i < props.clothing.socialRate5
                  ? 'text-blue-500'
                  : 'text-gray-300'}"
              >
                ★
              </div>
            {/each}
          </div>
        </div>
      {/if}
    </div>
    <section class="mt-6">
      <h2 class="text-sm font-medium text-gray-500 mb-3">Où acheter</h2>
      <div class="space-y-2">
        {#each props.links as link}
          <a
            href={link.url}
            target="_blank"
            rel="noopener noreferrer"
            class="block w-full px-4 py-3 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors items-center justify-between"
          >
            <div>
              {#if link.currentPrice}
                <div class="flex items-center gap-2">
                  <span
                    class={link.currentPrice.isOnSale
                      ? "text-red-600 font-bold"
                      : ""}
                  >
                    {formatPrice(link.currentPrice.priceCts)}
                  </span>
                  {#if link.currentPrice.isOnSale}
                    <span
                      class="text-sm px-2 py-0.5 bg-red-100 text-red-600 rounded-full"
                      >Soldes</span
                    >
                  {/if}
                </div>
              {/if}
              <span class="text-blue-600 text-sm"
                >Voir sur le site marchand</span
              >
            </div>
            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="h-5 w-5 text-gray-400"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"
              />
            </svg>
          </a>
        {/each}
      </div>
    </section>
  </div>
</div> -->
