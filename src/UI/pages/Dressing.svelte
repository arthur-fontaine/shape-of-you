<script lang="ts">
    import type {IDressing} from "../types/Dressing";
    import DressingPiece from "../components/DressingPiece.svelte";
    import InfoUser from "../components/InfoUser.svelte";
    import type { IUser } from "../types/User";
    import SearchResultSkeleton from "../components/SearchResultSkeleton.svelte";

    const { dressing, user }: {
        dressing: IDressing[];
        user: IUser,
    } = $props();
</script>

<InfoUser {user} />

<div class="flex flex-col px-4">
    <ul class="flex flex-col gap-3 mt-4">
        {#each dressing as {clothing: item}}
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