import React from 'react'

export default function TopTitleCard({ title, value, bg, border }) {
  return (
    <div className={`col h-full w-full rounded-[6px] flex flex-col border ${border}`}>
      <div className="col text-center">
        <p className="text-[40px]">{title}</p>
      </div>
      <div className={`col h-full rounded-[6px] flex justify-center items-center ${bg}`}>
        <div className="text-center">
          <span className="text-stroke"><p className="text-[64px]">{value}</p></span>
        </div>
      </div>
    </div>
  )
}
