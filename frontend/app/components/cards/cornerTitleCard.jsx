import React from 'react'

export default function CornerTitleCard({ title, value, bg }) {
  return (
    <div className={`col h-full w-full rounded-[6px] p-[1px] ${bg}`}>
    {/* <div className="col h-full w-full rounded-[6px] p-[1px] bg-[#05A305]"> */}
      <div className="h-[60px] w-[115px] bg-[#D9D9D9] rounded-[6px]">
        <div className="flex justify-center items-center">
          <p className="text-[48px]">{title}</p>
        </div>
      </div>
      <div className="flex justify-center">
        <span className="text-stroke">
          <p className="text-[64px] text-white">{value} %</p>
        </span>
      </div>
    </div>
  )
}
