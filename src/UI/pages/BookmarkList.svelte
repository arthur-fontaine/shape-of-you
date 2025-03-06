<script lang="ts">
    import type { IUser } from "../types/User";
    import InfoUser from "../components/InfoUser.svelte";
    import * as Sheet from "../components/sheet";

    const {
        user,
    }: {
        user: IUser;
    } = $props();
</script>

<InfoUser {user} />
<div class="flex flex-col p-4">
    <!-- <a href="/profile/bookmarks/new" class="button w-full flex items-center justify-center gap-2">
        <span class="icon-[tabler--bookmark-plus]"></span>
        Créer une nouvelle collection
    </a> -->
    <Sheet.Root>
        <Sheet.Trigger>
            <button class="button w-full flex items-center justify-center gap-2">
                <span class="icon-[tabler--bookmark-plus]"></span>
                Créer une nouvelle collection
            </button>
        </Sheet.Trigger>
        <Sheet.Content side="bottom">
          <iframe
            src="/profile/bookmarks/new"
            title="Create a new collection"
            class="w-full h-96"
          >
          </iframe>
        </Sheet.Content>
      </Sheet.Root>
    <div class="grid grid-cols-2 gap-4 mt-8">
        {#each user.clothingLists as clothingList (clothingList.id)}
            <div>
                <a href="/profile/bookmarks/{clothingList.id}">
                    <div class="grid grid-cols-2 gap-0.5 grid-rows-2 rounded-lg overflow-hidden">
                        {#each clothingList.clothingCollection.slice(0, 4) as clothing}
                            <img
                                src={clothing.imageUrl}
                                alt={clothing.name}
                            />
                        {/each}
                        {#each Array(4 - clothingList.clothingCollection.length) as _}
                            <div class="bg-input-placeholder/15 min-w-1/2 aspect-square"></div>
                        {/each}
                    </div>
                    <p class="mt-2 ml-0.5">{clothingList.name}</p>
                </a>
            </div>
        {/each}
    </div>
</div>
