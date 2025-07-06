import CornerTitleCard from '@/app/components/cards/cornerTitleCard';
import HeaderSection from '@/app/components/header/sectionHeader';
import Link from 'next/link';
import React from 'react'

async function getData() {
  const res = await fetch(`http://192.168.10.75:5000/api/production/percen`, {
    next: {
      revalidate: 0,
    },
  });
  if (!res.ok) {
    throw new Error("Failed to fetch data");
  }
  return res.json()
}

export default async function PercenSection() {
  const res = await getData()
  const { data } = res.payload
  return (
    <div className="w-[400px] rounded-[8px] bg-[#D9D9D9] p-[10px] flex flex-col gap-[38px] h-full">
      <HeaderSection name='PRODUCTION' />
      <div className="col h-full flex flex-col gap-6">
        {data.map((element) => {
          const bg = element.LineType == 'CAM' ? element.RataRata <= 60 ? 'bg-[#FF0000]' : element.RataRata > 60 && element.RataRata <= 80 ? 'bg-[#FF9900]' : 'bg-[#05A305]' : element.RataRata <= 70 ? 'bg-[#FF0000]' : element.RataRata > 70 && element.RataRata <= 85 ? 'bg-[#FF9900]' : 'bg-[#05A305]'
          return (
            <Link key={element.LineType} className="h-full"
              href={{
              pathname: "/pages/productions/detail",
              query: {
                line: element.LineType,
                percen: element.RataRata,
              }
              }}
              // as={'/pages/productions/detail'}
            >
              <CornerTitleCard value={(element.RataRata).toFixed(2)} title={element.LineType} bg={bg} />
            </Link>
          )
        })}
      </div>
    </div>
  )
}
