'use client'

import HeaderSection from '@/app/components/header/sectionHeader'
import React, { useEffect, useState } from 'react'
import DepartementSection from './departementSection'
import Link from 'next/link'

export default function QualitySection({ value }) {

  return (
    <Link href="/pages/qualities" className="w-full h-full" prefetch={false}>
      <div className="col h-full rounded-lg bg-[#D9D9D9] p-3 flex flex-col gap-8">
        <HeaderSection name='QUALITY' />
        <div className="col h-full flex gap-3">
          <DepartementSection departement='IPQC' value={value} />
          <DepartementSection departement='OQC' value={value} />
        </div>
      </div>
    </Link>
  )
}
