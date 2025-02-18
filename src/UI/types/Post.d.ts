export interface IPost {
  authorId: string;
  authorName: string;
  mediaUrls: string[];
  myRate: number | null | undefined;
  postId: number;
  text: string;
}