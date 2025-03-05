export interface IPost {
  id: number;
  authorId: string;
  authorName: string;
  mediaUrls: string[];
  myRate: number | null | undefined;
  postId: number;
  text: string;
}