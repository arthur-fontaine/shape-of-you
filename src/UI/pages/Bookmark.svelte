<script lang="ts">
    import type { IClothingList } from "../types/ClothingList";
    import type { IClothing } from "../types/Clothing";
    import ClothingCollection from "../components/ClothingCollection.svelte";
    import { createMutation } from "../utils/query";
    import SearchResultSkeleton from "../components/SearchResultSkeleton.svelte";

    const props: {
        clothingList: IClothingList;
        clothingCollection: IClothing[];
    } = $props();

    const bookmark = props.clothingList;
    const clothingCollection = props.clothingCollection;

    const bookmarkMutation = createMutation<
        unknown,
        {
            bookmarkId: number;
        }
    >(`/profile/bookmarks/delete`);
    function onDelete() {
        $bookmarkMutation.mutate(
            { bookmarkId: bookmark.id },
            {
                onSuccess: () => (location.pathname = "/profile/bookmarks"),
            },
        );
    }
</script>

<div class="absolute right-4 top-6">
    <button onclick={onDelete} aria-label="Supprimer le bookmark">
        <span class="icon-[tabler--trash] text-2xl"></span>
    </button>
</div>

<div class="px-4">
    <h1 class="text-2xl font-bold">{bookmark.name}</h1>
    <p class="text-sm">
        {bookmark.clothingCollection.length} vêtement{bookmark.clothingCollection.length > 1 ? "s" : ""} enregistré{bookmark.clothingCollection.length > 1 ? "s" : ""}
    </p>
    <ul class="flex flex-col gap-3 mt-4">
        {#each clothingCollection as item}
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
</div>
