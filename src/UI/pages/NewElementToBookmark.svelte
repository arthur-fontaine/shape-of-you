<script lang="ts">
    import AddToBookmarkListBox from "../components/AddToBookmarkListBox.svelte";
    import type { IClothingList } from "../types/ClothingList";
    import { createMutation } from "../utils/query";

    const props: {
        clothingList: IClothingList[];
        clothingId: string;
    } = $props();

    const clothingList = props.clothingList;
    const clothingId = props.clothingId;
</script>

<div class="container mx-auto space-y-4">
    <h1 class="text-xl font-bold mb-6">Sélectionner une collection</h1>
    {#each clothingList as collection}
        <AddToBookmarkListBox
            clothingList={collection}
            clothingId={clothingId}
        />
    {/each}
    {#if clothingList.length === 0}
        <p class="text-center text-xs text-disabled">
            Vous n'avez pas de collection, créez-en une <a
                href="/profile/bookmarks"
                class="underline"
                target="_parent"
            >
                ici
            </a>.
        </p>
    {/if}
</div>
