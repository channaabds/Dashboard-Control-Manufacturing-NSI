import React from 'react'
import HeaderSection from '../header/sectionHeader'
import CornerTitleCard from '../cards/cornerTitleCard';

export default function ProductionSection({ value }) {
  if (value.length === 0) {
    value = [
      {
        PostDate: '2023-11-13T00:34:06.956Z',
        LineType: 'CAM',
        RataRata: 0,
      },
      {
        PostDate: '2023-11-13T00:34:06.956Z',
        LineType: 'LINE1',
        RataRata: 0,
      },
      {
        PostDate: '2023-11-13T00:34:06.956Z',
        LineType: 'LINE2',
        RataRata: 0
      },
      {
        PostDate: '2023-11-13T00:34:06.956Z',
        LineType: 'LINE3',
        RataRata: 0,
      },
    ]
  }
  return (
    <div className="w-[400px] rounded-[8px] bg-[#D9D9D9] p-[10px] flex flex-col gap-[38px] h-full">
      <HeaderSection name='PRODUCTION' />
      <div className="col h-full flex flex-col gap-6">
        {value.map((element) => {
          const bg = element.LineType == 'CAM' ? element.RataRata <= 60 ? 'bg-[#FF0000]' : element.RataRata > 60 && element.RataRata <= 80 ? 'bg-[#FF9900]' : 'bg-[#05A305]' : element.RataRata <= 70 ? 'bg-[#FF0000]' : element.RataRata > 70 && element.RataRata <= 85 ? 'bg-[#FF9900]' : 'bg-[#05A305]'
          return (
          <CornerTitleCard key={element.LineType} value={(element.RataRata).toFixed(2)} title={element.LineType} bg={bg} />
        )})}
      </div>
    </div>
  )
}
