<script lang="ts">
  import { debounce } from "lodash-es";
  import { createMutation } from "../utils/query";
  import SearchResultSkeleton from "../components/SearchResultSkeleton.svelte";
  
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

  let forceIsLoading = $state(false);
  const isLoading = $derived($search.isPending || forceIsLoading);

  function searchText(text: string) {
    const formData = new FormData();
    formData.append("q", text);
    $search.mutate(formData);
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
</script>

<div class="mt-18 px-4">
  <form>
    <div class="flex items-center input mb-8">
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
        <span class="icon-[tabler--camera] text-2xl text-input-placeholder"></span>
      </label>
    </div>
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
  {:else if $search.isError || $search.data?.length === 0}
    <div class="text-center py-8 text-disabled">
      Aucun résultat trouvé
    </div>
  {:else if $search.data}
    <ul class="flex flex-col gap-3">
      {#each $search.data as item}
        <li>
          <a href={`/clothing/${item.id}`}>
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
