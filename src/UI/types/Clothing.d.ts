export interface IClothing {
  id: number;
  name: string;
  type: string;
  imageUrl: string;
  color: string[];
  socialRate5: number;
  ecologyRate5: number;
  measurements: IClothingMeasurements;
}

export interface IClothingLink {
  id: number;
  url: string;
  currentPrice?: IClothingPrice;
}

export interface IClothingPrice {
  priceCts: number;
  isOnSale: boolean;
  registeredAt: string;
}

export interface IClothingMeasurements {
  chest?: number;
  waist?: number;
  length?: number;
}
