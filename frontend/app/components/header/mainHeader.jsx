'use client'

import Arrow from "@/app/components/icons/Arrow"

import React, { useEffect, useState } from 'react'
import { usePathname, useRouter } from 'next/navigation';

export default function MainHeader() {
  const [dateState, setDateState] = useState(new Date());
  useEffect(() => {
    setInterval(() => setDateState(new Date()), 30000);
  }, []);

  const pathname = usePathname()
  const router = useRouter()

  return (
    <div className="flex flex-row">
      <div className={`absolute top-7 left-7 ${pathname == '/' ? 'hidden' : ''}`}>
        <button onClick={() => router.back()}>
          <Arrow />
        </button>
      </div>
      <div className="col w-full h-[150px] flex justify-center items-center">
        <div className="basis-10/12 text-center">
          <p className=" text-[64px]">DASHBORD CONTROL MANUFACTURING</p>
          <p className=" text-[48px]">
            {dateState.toLocaleDateString('en-GB', {
              day: 'numeric',
              month: 'long',
              year: 'numeric',
            })}
          </p>
        </div>
      </div>
    </div>
  )
}
