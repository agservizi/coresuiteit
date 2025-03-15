"use client"

import { LineChart, Line, XAxis, YAxis, CartesianGrid, Tooltip, Legend, ResponsiveContainer } from "recharts"

const data = [
  { name: "1 Giu", Fastweb: 400, Iliad: 240, WindTre: 200, SkyWifi: 180, SkyTV: 100, PianetaFibra: 150 },
  { name: "5 Giu", Fastweb: 300, Iliad: 398, WindTre: 220, SkyWifi: 200, SkyTV: 120, PianetaFibra: 160 },
  { name: "10 Giu", Fastweb: 200, Iliad: 300, WindTre: 250, SkyWifi: 190, SkyTV: 140, PianetaFibra: 180 },
  { name: "15 Giu", Fastweb: 278, Iliad: 320, WindTre: 290, SkyWifi: 210, SkyTV: 160, PianetaFibra: 190 },
  { name: "20 Giu", Fastweb: 189, Iliad: 400, WindTre: 310, SkyWifi: 260, SkyTV: 180, PianetaFibra: 220 },
  { name: "25 Giu", Fastweb: 239, Iliad: 380, WindTre: 290, SkyWifi: 300, SkyTV: 200, PianetaFibra: 240 },
  { name: "30 Giu", Fastweb: 349, Iliad: 430, WindTre: 340, SkyWifi: 320, SkyTV: 210, PianetaFibra: 260 },
]

export default function DashboardChart() {
  return (
    <ResponsiveContainer width="100%" height={350}>
      <LineChart
        data={data}
        margin={{
          top: 5,
          right: 10,
          left: 10,
          bottom: 0,
        }}
      >
        <CartesianGrid strokeDasharray="3 3" stroke="var(--border)" opacity={0.4} />
        <XAxis dataKey="name" stroke="var(--muted-foreground)" fontSize={12} tickLine={false} axisLine={false} />
        <YAxis stroke="var(--muted-foreground)" fontSize={12} tickLine={false} axisLine={false} />
        <Tooltip
          contentStyle={{
            backgroundColor: "var(--background)",
            borderColor: "var(--border)",
            borderRadius: "var(--radius)",
            boxShadow: "0 4px 12px rgba(0, 0, 0, 0.1)",
          }}
        />
        <Legend wrapperStyle={{ paddingTop: 10 }} />
        <Line type="monotone" dataKey="Fastweb" stroke="#FF6B6B" strokeWidth={2} dot={{ r: 4 }} activeDot={{ r: 6 }} />
        <Line type="monotone" dataKey="Iliad" stroke="#4ECDC4" strokeWidth={2} dot={{ r: 4 }} activeDot={{ r: 6 }} />
        <Line type="monotone" dataKey="WindTre" stroke="#FFD166" strokeWidth={2} dot={{ r: 4 }} activeDot={{ r: 6 }} />
        <Line type="monotone" dataKey="SkyWifi" stroke="#118AB2" strokeWidth={2} dot={{ r: 4 }} activeDot={{ r: 6 }} />
        <Line type="monotone" dataKey="SkyTV" stroke="#073B4C" strokeWidth={2} dot={{ r: 4 }} activeDot={{ r: 6 }} />
        <Line
          type="monotone"
          dataKey="PianetaFibra"
          stroke="#6A0572"
          strokeWidth={2}
          dot={{ r: 4 }}
          activeDot={{ r: 6 }}
        />
      </LineChart>
    </ResponsiveContainer>
  )
}

