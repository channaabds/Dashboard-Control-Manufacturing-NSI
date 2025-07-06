import React from "react";
import Image from "next/image";


const BackButton = () => {
  return (
    <div>
      <Image src={'/arrow.png'} alt="Back" width={30} height={30} />
    </div>
  );
};

export default BackButton;
