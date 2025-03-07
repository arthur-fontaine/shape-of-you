<script lang="ts">
    import type { IUser } from "../types/User";
    import profile from "../assets/icons/profile.svg";
    import Avatar from "svelte-boring-avatars";
    import { createMutation } from "../utils/query";

    let {
        user,
        userRelationship = "self",
    }: {
        user: IUser;
        userRelationship?: "self" | "friend" | "none";
    } = $props();

    const addFriendMutation = createMutation(`${location.href}/add-friend`, undefined, {
        onSuccess: () => {
            userRelationship = "friend";
            user = { ...user, friendsCount: user.friendsCount + 1 };
        },
    });
    const removeFriendMutation = createMutation(`${location.href}/remove-friend`, undefined, {
        onSuccess: () => {
            userRelationship = "none";
            user = { ...user, friendsCount: user.friendsCount - 1 };
        },
    });

    const handleAddFriend = () => {
        $addFriendMutation.mutate();
    };

    const handleRemoveFriend = () => {
        $removeFriendMutation.mutate();
    };
</script>

<div class="shadow-md mb-4">
    <div class="max-w-5xl mx-auto p-4 pb-0">
        <div class="flex items-center space-x-4">
            <Avatar
                size={64}
                name={user.name}
                variant="beam"
                colors={["#92A1C6", "#146A7C", "#F0AB3D", "#C271B4", "#C20D90"]}
            />
            <div class="grid grid-cols-2 gap-x-4 gap-y-0.5">
                <h2 class="text-xl font-bold col-span-2">{user.name}</h2>
                <span
                    ><span class="font-bold">{user.posts.length}</span> posts</span
                >
                <span
                    ><span class="font-bold">{user.friendsCount}</span> followers</span
                >
            </div>
        </div>
        {#if userRelationship !== "self"}
            <button
                class="button mt-6 w-full {userRelationship === "friend" ? "secondary" : "primary"}"
                onclick={userRelationship === "friend"
                    ? handleRemoveFriend
                    : handleAddFriend}
            >
                {userRelationship === "friend" ? "Se désabonner" : "S'abonner"}
            </button>
        {/if}
        <div
            class="mt-8 flex *:flex-1 *:justify-center *:text-2xl *:flex *:pb-4"
        >
            <a
                href="/profile"
                aria-label="Profile"
                class={location.pathname === "/profile"
                    ? "border-b-2 border-b-label"
                    : "text-disabled"}
            >
                <span class="icon-[tabler--layout-grid]"></span>
            </a>
            <a
                href="/profile/bookmarks"
                aria-label="Bookmarks"
                class={location.pathname === "/profile/bookmarks"
                    ? "border-b-2 border-b-label"
                    : "text-disabled"}
            >
                <span class="icon-[tabler--bookmark]"></span>
            </a>
            <a
                href="/profile/dressing"
                aria-label="Dressing"
                class={location.pathname === "/profile/dressing"
                    ? "border-b-2 border-b-label"
                    : "text-disabled"}
            >
                <span class="icon-[tabler--hanger]"></span>
            </a>
            <a
                href="/profile/ai"
                aria-label="Intelligence Artificielle"
                class={location.pathname === "/profile/ai"
                    ? "border-b-2 border-b-label"
                    : "text-disabled"}
            >
                <span class="icon-[tabler--sparkles]"></span>
            </a>
        </div>
    </div>
</div>
