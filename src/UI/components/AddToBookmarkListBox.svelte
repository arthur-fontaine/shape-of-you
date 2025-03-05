<script lang="ts">
  import type { IClothingList } from "../types/ClothingList";
  import { createMutation } from "../utils/query";

  let {
    clothingList,
    clothingId,
  }: {
    clothingList: IClothingList;
    clothingId: string;
  } = $props();

  const bookmark = createMutation<
    void,
    {
      collection: string;
      clothingId: string;
    }
  >(undefined, undefined, {
    onMutate: () => {
      clothingList = {
        ...clothingList,
        clothingCollection: [
          ...clothingList.clothingCollection,
          {
            id: Number(clothingId),
          } as never,
        ],
      };
    },
    onError: () => {
      clothingList = {
        ...clothingList,
        clothingCollection: clothingList.clothingCollection.filter(
          (clothing) => clothing.id.toString() !== clothingId.toString(),
        ),
      };
    },
  });

  const unbookmark = createMutation<
    void,
    {
      collection: string;
      clothingId: string;
    }
  >(`/profile/bookmarks/${clothingList.id}/remove-item`, undefined, {
    onMutate: () => {
      clothingList = {
        ...clothingList,
        clothingCollection: clothingList.clothingCollection.filter(
          (clothing) => clothing.id.toString() !== clothingId.toString(),
        ),
      };
    },
    onError: () => {
      clothingList = {
        ...clothingList,
        clothingCollection: [
          ...clothingList.clothingCollection,
          {
            id: Number(clothingId),
          } as never,
        ],
      };
    },
  });

  const isBookmarkedInThisList = $derived(() =>
    clothingList.clothingCollection.some(
      (clothing) => clothing.id.toString() === clothingId.toString(),
    ),
  );
</script>

<button
  type="button"
  class="flex items-center justify-between p-4 border border-radio-box color-radio-box rounded-lg w-full"
  onclick={() =>
    isBookmarkedInThisList()
      ? $unbookmark.mutate({ collection: clothingList.id.toString(), clothingId })
      : $bookmark.mutate({ collection: clothingList.id.toString(), clothingId })
  }
>
  <span>{clothingList.name}</span>
  <span class="{isBookmarkedInThisList() ? 'icon-[tabler--x]' : 'icon-[tabler--plus]'}"></span>
</button>
