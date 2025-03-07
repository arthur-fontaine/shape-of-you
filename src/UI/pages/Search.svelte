<script lang="ts">
  import { debounce } from "lodash-es";
  import { createMutation } from "../utils/query";
  import SearchResultSkeleton from "../components/SearchResultSkeleton.svelte";
  import FilterButton from "../components/FilterButton.svelte";
  import FilterSheet from "../components/FilterSheet.svelte";
  import PriceRangeSlider from "../components/PriceRangeSlider.svelte";

  let {
    maxClothingPrice,
  }: {
    maxClothingPrice: number;
  } = $props();

  const search = createMutation<
    {
      id: number;
      name: string;
      imageUrl: string;
      type: string;
      description: string | null;
    }[],
    FormData
  >();

  const searchAi = createMutation<
    {
      id: number;
      name: string;
      imageUrl: string;
      type: string;
      description: string | null;
    }[],
    FormData
  >('/search-by-ai');

  interface FilterOption {
    value: string;
    label: string;
  }

  interface FilterResponse {
    colors: FilterOption[];
    types: FilterOption[];
  }

  let colorOptions = $state<FilterOption[]>([]);
  let typeOptions = $state<FilterOption[]>([]);

  let selectedColors = $state<string[]>([]);
  let selectedTypes = $state<string[]>([]);

  let forceIsLoading = $state(false);
  let searchQuery = $state("");

  async function fetchFilterOptions() {
    const response = await fetch("/api/search/filters");

    const data: FilterResponse = await response.json();

    colorOptions = data.colors;
    typeOptions = data.types;
  }

  fetchFilterOptions();

  async function searchText(text: string) {
    $searchAi.reset();

    const formData = new FormData();
    formData.append("q", text);

    selectedColors.forEach((color) => {
      formData.append("colors[]", color);
    });

    selectedTypes.forEach((type) => {
      formData.append("types[]", type);
    });

    if (hasPriceFilter) {
      formData.append("price_min", minPrice.toString());
      formData.append("price_max", maxPrice.toString());
    }

    if (
      selectedColors.length > 0 ||
      selectedTypes.length > 0 ||
      hasPriceFilter
    ) {
      formData.append("exclude_users", "1");
    }

    const data = await $search.mutateAsync(formData);

    if (data.length === 0) {
      $searchAi.mutate(formData);
    }
  }

  function searchImage(image: File) {
    const formData = new FormData();
    formData.append("image", image);

    $search.mutate(formData);
  }

  const _debouncedSearchText = debounce(function (text: string) {
    searchText(text);
    forceIsLoading = false;
  }, 500);
  function debouncedSearchText(text: string) {
    forceIsLoading = true;
    searchQuery = text;
    _debouncedSearchText(text);
  }

  function onSearchInput(event: Event) {
    const input = event.target as HTMLInputElement;
    if (input.value === "") return;
    debouncedSearchText(input.value);
  }

  function onImageSelected(event: Event) {
    const input = event.target as HTMLInputElement;
    if (input.files && input.files.length > 0) {
      searchImage(input.files[0]);
    }
  }

  let colorSheetOpen = $state(false);
  let typeSheetOpen = $state(false);
  let priceSheetOpen = $state(false);

  let minPrice = $state(0);
  let maxPrice = $state(maxClothingPrice);
  console.log(maxClothingPrice);
  let priceRange = $state([0, maxClothingPrice]);
  let hasPriceFilter = $state(false);

  function toggleColorFilter() {
    colorSheetOpen = !colorSheetOpen;
    typeSheetOpen = false;
    priceSheetOpen = false;
  }

  function toggleTypeFilter() {
    typeSheetOpen = !typeSheetOpen;
    colorSheetOpen = false;
    priceSheetOpen = false;
  }

  function togglePriceFilter() {
    priceSheetOpen = !priceSheetOpen;
    colorSheetOpen = false;
    typeSheetOpen = false;
  }

  function onColorSheetClose() {
    colorSheetOpen = false;
  }

  function onTypeSheetClose() {
    typeSheetOpen = false;
  }

  function onPriceSheetClose() {
    priceSheetOpen = false;
  }

  function applyPriceFilter() {
    minPrice = priceRange[0];
    maxPrice = priceRange[1];
    hasPriceFilter = minPrice > 0 || maxPrice < 1000;

    triggerSearchWithCurrentState();

    priceSheetOpen = false;
  }

  function resetPriceFilter() {
    priceRange = [0, maxClothingPrice];
    minPrice = 0;
    maxPrice = maxClothingPrice;
    hasPriceFilter = false;

    triggerSearchWithCurrentState();
  }

  function toggleColorSelection(color: string) {
    if (selectedColors.includes(color)) {
      selectedColors = selectedColors.filter((c) => c !== color);
    } else {
      selectedColors = [...selectedColors, color];
    }

    triggerSearchWithCurrentState();
  }

  function toggleTypeSelection(type: string) {
    if (selectedTypes.includes(type)) {
      selectedTypes = selectedTypes.filter((t) => t !== type);
    } else {
      selectedTypes = [...selectedTypes, type];
    }

    triggerSearchWithCurrentState();
  }

  function clearFilters() {
    selectedColors = [];
    selectedTypes = [];
    resetPriceFilter();

    triggerSearchWithCurrentState();
  }

  function triggerSearchWithCurrentState() {
    if (searchQuery) {
      debouncedSearchText(searchQuery);
    } else {
      debouncedSearchText(" ");
    }
  }

  const results = $derived((($search.data?.length ?? 0) > 0 ? $search.data : $searchAi.data) ?? []);
  const isError = $derived(results.length === 0 ? ($search.isError || $searchAi.isError) : false);
  const isLoading = $derived($search.isPending || $searchAi.isPending || forceIsLoading);
</script>

<div class="mt-18 px-4 mb-22">
  <form class="mb-6">
    <div class="flex items-center input mb-3">
      <input
        type="text"
        name="q"
        placeholder="Rechercher..."
        class="flex-1"
        oninput={onSearchInput}
      />

      <label class="flex">
        <input
          type="file"
          name="image"
          accept="image/png, image/jpeg"
          hidden
          onchange={onImageSelected}
        />
        <span class="icon-[tabler--camera] text-2xl text-input-placeholder"
        ></span>
      </label>
    </div>

    <div class="flex gap-2 mb-4 overflow-x-auto w-max">
      <FilterButton
        label="Couleur"
        active={colorSheetOpen}
        onClick={toggleColorFilter}
      />

      <FilterButton
        label="Type"
        active={typeSheetOpen}
        onClick={toggleTypeFilter}
      />

      <FilterButton
        label="Prix"
        active={priceSheetOpen}
        onClick={togglePriceFilter}
      />

      {#if selectedColors.length > 0 || selectedTypes.length > 0 || hasPriceFilter}
        <button
          type="button"
          class="ml-auto text-sm text-primary underline"
          onclick={clearFilters}
        >
          Effacer les filtres
        </button>
      {/if}
    </div>

    <FilterSheet
      bind:open={colorSheetOpen}
      title="Couleurs"
      onClose={onColorSheetClose}
      onClear={clearFilters}
      hasFilters={selectedColors.length > 0}
    >
      <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
        {#each colorOptions as color}
          <label class="flex items-center gap-2 cursor-pointer">
            <input
              type="checkbox"
              class="rounded-sm border-border checked:bg-primary hidden peer"
              checked={selectedColors.includes(color.value)}
              onchange={() => toggleColorSelection(color.value)}
            />
            <span
              class="size-3 rounded-full peer-checked:ring-label peer-checked:ring-2 ring-offset-1"
              style="background-color: {color.value};"
            ></span>
            {color.label}
          </label>
        {/each}
      </div>
    </FilterSheet>

    <FilterSheet
      bind:open={typeSheetOpen}
      title="Types de vêtements"
      onClose={onTypeSheetClose}
      onClear={clearFilters}
      hasFilters={selectedTypes.length > 0}
    >
      <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
        {#each typeOptions as type}
          <label class="flex items-center gap-2 cursor-pointer">
            <input
              type="checkbox"
              class="hidden peer"
              checked={selectedTypes.includes(type.value)}
              onchange={() => toggleTypeSelection(type.value)}
            />
            <span class="group relative flex items-center peer-checked:bg-ui-background rounded-xs border border-ui-background/20 peer-checked:border-ui-background">
              <span class="group-peer-checked:visible invisible icon-[tabler--check] text-ui-surface"></span>
            </span>
            {type.label}
          </label>
        {/each}
      </div>
    </FilterSheet>

    <FilterSheet
      bind:open={priceSheetOpen}
      title="Fourchette de prix"
      onClose={onPriceSheetClose}
      onClear={resetPriceFilter}
      hasFilters={hasPriceFilter}
    >
      <div class="p-4">
        <PriceRangeSlider
          min={0}
          max={maxClothingPrice}
          bind:values={priceRange}
        />

        <div class="flex flex-col sm:flex-row gap-4 mt-6">
          <div class="flex items-center gap-2">
            <label for="min-price" class="text-sm whitespace-nowrap">Min:</label
            >
            <input
              id="min-price"
              type="number"
              min="0"
              max={priceRange[1] - 1}
              class="input text-sm w-full"
              bind:value={priceRange[0]}
            />
          </div>

          <div class="flex items-center gap-2">
            <label for="max-price" class="text-sm whitespace-nowrap">Max:</label
            >
            <input
              id="max-price"
              type="number"
              min={priceRange[0] + 1}
              max={maxClothingPrice}
              class="input text-sm w-full"
              bind:value={priceRange[1]}
            />
          </div>
        </div>

        <div class="mt-8 flex justify-end">
          <button type="button" class="button px-6" onclick={applyPriceFilter}>
            Appliquer
          </button>
        </div>
      </div>
    </FilterSheet>
  </form>

  {#if isLoading}
    <ul class="flex flex-col gap-3">
      {#each Array.from({ length: 5 }) as _}
        <li>
          <SearchResultSkeleton>
            <div
              slot="image"
              class="size-20 rounded-result-image bg-skeleton-background"
            ></div>
            <span
              slot="name"
              class="text-base font-semibold bg-skeleton-background rounded select-none text-transparent"
            >
              NAME
            </span>
            <span
              slot="description"
              class="text-base font-normal bg-skeleton-background rounded select-none text-transparent"
            >
              DESCRIPTION DESCRIPTION DESCRIPTION
            </span>
          </SearchResultSkeleton>
        </li>
      {/each}
    </ul>
  {:else if isError || results.length === 0}
    <div class="text-center py-8 text-disabled">Aucun résultat trouvé</div>
  {:else if results}
    <ul class="flex flex-col gap-3">
      {#each results as item}
        <li>
          <a href={`/${'email' in item ? 'users' : 'clothing'}/${item.id}`}>
            <SearchResultSkeleton>
              <img slot="image" src={item.imageUrl} alt={item.name} />
              <span slot="name">{item.name}</span>
              <span slot="description">{item.description ?? ""}</span>
            </SearchResultSkeleton>
          </a>
        </li>
      {/each}
    </ul>
  {/if}
</div>
