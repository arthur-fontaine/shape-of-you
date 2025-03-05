<script lang="ts">
    import { createMutation } from "../utils/query.js";

    const bookmark = createMutation<{ bookmarkListUrl: string }, FormData>();

    function onSubmit(e: SubmitEvent) {
        const formData = new FormData(e.target as HTMLFormElement);
        const sentFormData = new FormData();
        sentFormData.append("name", formData.get("name") as string);
        $bookmark.mutate(sentFormData, {
            onSuccess: ({ bookmarkListUrl }) => {
                window.parent.location.href = bookmarkListUrl;
            },
        });
    }
</script>

<div class="px-4 pt-10">
    <form on:submit|preventDefault={onSubmit}>
        <div class="flex items-center input">
            <input
                type="text"
                name="name"
                placeholder="Nom de la liste"
                class="flex-1"
            />
        </div>

        <button type="submit" class="button w-full mt-4">
            Créer
        </button>
    </form>
</div>
