import { IClothing } from './Clothing';
export interface IClothingList {
    id: number;
    name: string;
    isBookmarked: boolean;
    clothingCollection: IClothing[];
}