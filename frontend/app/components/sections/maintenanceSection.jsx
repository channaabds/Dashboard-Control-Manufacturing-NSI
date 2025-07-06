import React from 'react'
import HeaderSection from '../header/sectionHeader'
import TopTitleCard from '../cards/topTitleCard'

export default function MaintenanceSection({ value }) {
  const { currentMonth, lastMonth, beforeLastMonth } = value
  const bgCurrent = parseFloat(currentMonth) <= 70 ? 'bg-[#05A305]' : (parseFloat(currentMonth) > 70) && (parseFloat(currentMonth) <= 90) ? 'bg-[#FF9900]' : 'bg-[#FF0000]'
  const bgLast = parseFloat(lastMonth) <= 70 ? 'bg-[#05A305]' : (parseFloat(lastMonth) > 70) && (parseFloat(lastMonth) <= 90) ? 'bg-[#FF9900]' : 'bg-[#FF0000]'
  const bgBefore = parseFloat(beforeLastMonth) <= 70 ? 'bg-[#05A305]' : (parseFloat(beforeLastMonth) > 70) && (parseFloat(beforeLastMonth) <= 90) ? 'bg-[#FF9900]' : 'bg-[#FF0000]'
  const borderCurrent = parseFloat(currentMonth) <= 70 ? 'border-[#05A305]' : (parseFloat(currentMonth) > 70) && (parseFloat(currentMonth) <= 90) ? 'border-[#FF9900]' : 'border-[#FF0000]'
  const borderLast = parseFloat(lastMonth) <= 70 ? 'border-[#05A305]' : (parseFloat(lastMonth) > 70) && (parseFloat(lastMonth) <= 90) ? 'border-[#FF9900]' : 'border-[#FF0000]'
  const borderBefore = parseFloat(beforeLastMonth) <= 70 ? 'border-[#05A305]' : (parseFloat(beforeLastMonth) > 70) && (parseFloat(beforeLastMonth) <= 90) ? 'border-[#FF9900]' : 'border-[#FF0000]'
  return (
    <div className="w-[400px] rounded-[8px] bg-[#D9D9D9] p-[10px] flex flex-col gap-[38px] h-full">
      <HeaderSection name='MAINTENANCE' />
      <div className="col h-full flex flex-col gap-6">
        <TopTitleCard value={`${currentMonth} %`} title='BULAN INI' bg={bgCurrent} border={borderCurrent} />
        <TopTitleCard value={`${lastMonth} %`} title='1 BULAN LALU' bg={bgLast} border={borderLast} />
        <TopTitleCard value={`${beforeLastMonth} %`} title='2 BULAN LALU' bg={bgBefore} border={borderBefore} />
      </div>
    </div>
  )
}
